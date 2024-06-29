<?php
/*
Plugin Name: Unlimited Andrenaline V2
Description: API Intergation
Author: WebVortex
Text Domain: unlimited-andrenaline-v2
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Requests')) {
    require_once ABSPATH . WPINC . '/class-requests.php';
}


define('MY_PLUGIN_DIR_PATH', untrailingslashit(plugin_dir_path(__FILE__)));

require_once MY_PLUGIN_DIR_PATH . '/class-tgm-plugin-activation.php';
require_once MY_PLUGIN_DIR_PATH . '/inc/admin-settings.php';

add_action('tgmpa_register', 'unlimited_andrenaline_v2_register_required_plugins');

function unlimited_andrenaline_v2_register_required_plugins()
{
    $plugins = array(
        array(
            'name' => 'Advanced Custom Fields PRO', // The plugin name.
            'slug' => 'advanced-custom-fields-pro', // The plugin slug (typically the folder name).
            'source' => 'https://beez.one/advanced-custom-fields-pro.zip', // The plugin source.
            'required' => true, // If false, the plugin is only 'recommended' instead of required.
            'external_url' => 'https://beez.one/advanced-custom-fields-pro.zip', // If set, overrides default API URL and points to an external URL.
        ),
    );

    $config = array(
        'id' => 'unlimited-andrenaline-v2',
        'default_path' => '',
        'menu' => 'tgmpa-install-plugins',
        'parent_slug' => 'plugins.php',
        'capability' => 'manage_options',
        'has_notices' => true,
        'dismissable' => true,
        'is_automatic' => false,
        'message' => '',
    );

    tgmpa($plugins, $config);
}

function unlimited_andrenaline_v2_custom_post_type()
{
    $labels = array(
        'name' => _x('Activities', 'Post Type General Name', 'unlimited-andrenaline-v2'),
        'singular_name' => _x('Activity', 'Post Type Singular Name', 'unlimited-andrenaline-v2'),
        'menu_name' => __('Activities', 'unlimited-andrenaline-v2'),
        'name_admin_bar' => __('Activity', 'unlimited-andrenaline-v2'),
        'archives' => __('Item Archives', 'unlimited-andrenaline-v2'),
        'attributes' => __('Item Attributes', 'unlimited-andrenaline-v2'),
        'parent_item_colon' => __('Parent Item:', 'unlimited-andrenaline-v2'),
        'all_items' => __('All Items', 'unlimited-andrenaline-v2'),
        'add_new_item' => __('Add New Item', 'unlimited-andrenaline-v2'),
        'add_new' => __('Add New', 'unlimited-andrenaline-v2'),
        'new_item' => __('New Item', 'unlimited-andrenaline-v2'),
        'edit_item' => __('Edit Item', 'unlimited-andrenaline-v2'),
        'update_item' => __('Update Item', 'unlimited-andrenaline-v2'),
        'view_item' => __('View Item', 'unlimited-andrenaline-v2'),
        'view_items' => __('View Items', 'unlimited-andrenaline-v2'),
        'search_items' => __('Search Item', 'unlimited-andrenaline-v2'),
        'not_found' => __('Not found', 'unlimited-andrenaline-v2'),
        'not_found_in_trash' => __('Not found in Trash', 'unlimited-andrenaline-v2'),
        'featured_image' => __('Featured Image', 'unlimited-andrenaline-v2'),
        'set_featured_image' => __('Set featured image', 'unlimited-andrenaline-v2'),
        'remove_featured_image' => __('Remove featured image', 'unlimited-andrenaline-v2'),
        'use_featured_image' => __('Use as featured image', 'unlimited-andrenaline-v2'),
        'insert_into_item' => __('Insert into item', 'unlimited-andrenaline-v2'),
        'uploaded_to_this_item' => __('Uploaded to this item', 'unlimited-andrenaline-v2'),
        'items_list' => __('Items list', 'unlimited-andrenaline-v2'),
        'items_list_navigation' => __('Items list navigation', 'unlimited-andrenaline-v2'),
        'filter_items_list' => __('Filter items list', 'unlimited-andrenaline-v2'),
    );
    $args = array(
        'label' => __('Activity', 'unlimited-andrenaline-v2'),
        'description' => __('Activity Description', 'unlimited-andrenaline-v2'),
        'labels' => $labels,
        'supports' => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        'taxonomies' => array('category', 'post_tag'),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'post',
    );
    register_post_type('activity', $args);
}
add_action('init', 'unlimited_andrenaline_v2_custom_post_type', 0);

add_action('acf/init', 'import_acf_json_on_activation');

add_action('acf/init', 'import_acf_json_on_activation');

function import_acf_json_on_activation()
{
    if (get_option('acf_json_imported')) {
        return;
    }

    $file_path = plugin_dir_path(__FILE__) . 'vortex.json'; // Update the path accordingly

    if (import_acf_json($file_path)) {
        update_option('acf_json_imported', true);
        error_log('ACF fields imported successfully.');
    } else {
        error_log('Failed to import ACF fields.');
    }
}

function import_acf_json($file_path)
{
    if (!function_exists('acf_import_field_group')) {
        return false;
    }

    $json_data = file_get_contents($file_path);
    if (!$json_data) {
        return false;
    }

    $field_groups = json_decode($json_data, true);
    if (!$field_groups) {
        return false;
    }

    foreach ($field_groups as $field_group) {
        $existing_group = acf_get_field_group($field_group['key']);
        if ($existing_group) {
            acf_update_field_group(array_merge($existing_group, $field_group));
        } else {
            acf_import_field_group($field_group);
        }
    }

    return true;
}


register_deactivation_hook(__FILE__, 'reset_acf_json_import_flag');

function reset_acf_json_import_flag()
{
    delete_option('acf_json_imported');
}

add_action('admin_menu', 'unlimited_andrenaline_admin_menu');
function unlimited_andrenaline_admin_menu()
{
    add_menu_page('Import Activities', 'Import Activities', 'manage_options', 'import-activities', 'unlimited_andrenaline_import_activities_page');
}

function unlimited_andrenaline_import_activities_page()
{
    ?>
    <div class="wrap">
        <h1>Import Activities</h1>
        <form method="post" action="">
            <input type="hidden" name="unlimited_andrenaline_import_activities" value="1">
            <button type="submit" class="button button-primary">Import Activities</button>
        </form>
        <?php
        if (isset($_POST['unlimited_andrenaline_import_activities'])) {
            unlimited_andrenaline_import_activities();
        }
        ?>
        <div id="import-progress" style="margin-top: 20px;">
            <p><strong>Activities Found:</strong> <span
                    id="activities-found"><?php echo get_option('activities_found', 0); ?></span></p>
            <p><strong>Activities Imported:</strong> <span
                    id="activities-imported"><?php echo get_option('activities_imported', 0); ?></span></p>
        </div>
    </div>
    <?php
}

function unlimited_andrenaline_import_activities()
{
    $whitelabelid = get_option('activity_host_url_label');
    $api_host = get_option('activity_host_url');
    $api_key = get_option('activity_api_key');

    $endpoint = "activity";
    $url = $api_host . $endpoint;

    $url = add_query_arg(
        array(
            'whiteLabelId' => $whitelabelid
        ),
        $url
    );

    $args = array(
        'headers' => array(
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'apiKey' => $api_key,
            'Accept-Language' => 'en'
        )
    );

    $response = wp_remote_get($url, $args);

    if (is_wp_error($response)) {
        echo "Something went wrong: " . $response->get_error_message();
        return;
    }

    $response_body = wp_remote_retrieve_body($response);
    $data = json_decode($response_body, true);

    if (empty($data)) {
        echo "No activities found.";
        return;
    }

    $activities_found = count($data);
    update_option('activities_found', $activities_found);

    $batch_size = 5;
    $batches = array_chunk($data, $batch_size);
    $activities_imported = 0;

    foreach ($batches as $batch) {
        foreach ($batch as $activity) {
            $existing_post = get_posts(
                array(
                    'post_type' => 'activity',
                    'meta_key' => 'field_webvortex_activity_id',
                    'meta_value' => $activity['id'],
                )
            );

            if ($existing_post) {
                $post_id = $existing_post[0]->ID;
                wp_update_post(
                    array(
                        'ID' => $post_id,
                        'post_title' => wp_strip_all_tags($activity['id'] . ' - ' . $activity['title']),
                    )
                );
            } else {
                $post_id = wp_insert_post(
                    array(
                        'post_title' => wp_strip_all_tags($activity['id'] . ' - ' . $activity['title']),
                        'post_type' => 'activity',
                        'post_status' => 'publish'
                    )
                );
            }

            if ($post_id && !is_wp_error($post_id)) {
                update_field('field_webvortex_activity_id', $activity['id'], $post_id);
                update_field('field_webvortex_title', $activity['title'], $post_id);
                update_field('field_webvortex_provider_id', $activity['providerId'], $post_id);
                update_field('field_webvortex_rating', $activity['rating'], $post_id);
                update_field('field_webvortex_active_months', $activity['activeMonths'], $post_id);
                update_field('field_webvortex_category_ids', implode(', ', $activity['categoryIds']), $post_id);
                update_field('field_webvortex_description', $activity['description'], $post_id);

                // Photos
                if (!empty($activity['photos'])) {
                    $photos = array();
                    foreach ($activity['photos'] as $photo) {
                        $photos[] = array(
                            'field_webvortex_photo_id' => $photo['id'],
                            'field_webvortex_photo_full' => $photo['full'],
                            'field_webvortex_photo_thumb' => $photo['thumb'],
                            'field_webvortex_photo_title' => $photo['title']
                        );
                    }
                    update_field('field_webvortex_photos', $photos, $post_id);
                }

                // Itineraries
                if (!empty($activity['itineraries'])) {
                    $itineraries = array();
                    foreach ($activity['itineraries'] as $itinerary) {
                        $included = array();
                        if (!empty($itinerary['details']['included'])) {
                            foreach ($itinerary['details']['included'] as $item) {
                                $included[] = array(
                                    'field_webvortex_included_id' => $item['id'],
                                    'field_webvortex_included_title' => $item['title']
                                );
                            }
                        }

                        $do_not_forget = array();
                        if (!empty($itinerary['details']['doNotForget'])) {
                            foreach ($itinerary['details']['doNotForget'] as $item) {
                                $do_not_forget[] = array(
                                    'field_webvortex_do_not_forget_id' => $item['id'],
                                    'field_webvortex_do_not_forget_title' => $item['title']
                                );
                            }
                        }

                        $ratings = array();
                        if (!empty($itinerary['ratings'])) {
                            foreach ($itinerary['ratings'] as $rating) {
                                $ratings[] = array(
                                    'field_webvortex_rating_score' => $rating['score'],
                                    'field_webvortex_rating_text' => $rating['text'],
                                    'field_webvortex_rating_fullname' => $rating['fullname']
                                );
                            }
                        }

                        $languages = array();
                        if (!empty($itinerary['spokenLanguages'])) {
                            foreach ($itinerary['spokenLanguages'] as $language) {
                                $languages[] = array(
                                    'field_webvortex_language_id' => $language['id'],
                                    'field_webvortex_language_acrn' => $language['acrn'],
                                    'field_webvortex_language_title' => $language['title']
                                );
                            }
                        }

                        $itineraries[] = array(
                            'field_webvortex_itinerary_id' => $itinerary['id'],
                            'field_webvortex_itinerary_title' => $itinerary['title'],
                            'field_webvortex_itinerary_description' => $itinerary['description'],
                            'field_webvortex_itinerary_level' => $itinerary['level'],
                            'field_webvortex_itinerary_details' => array(
                                'field_webvortex_itinerary_included' => $included,
                                'field_webvortex_itinerary_do_not_forget' => $do_not_forget
                            ),
                            'field_webvortex_itinerary_ratings' => $ratings,
                            'field_webvortex_itinerary_min_age' => $itinerary['min_age'],
                            'field_webvortex_itinerary_spoken_languages' => $languages,
                            'field_webvortex_itinerary_booking_type' => $itinerary['bookingType'],
                            'field_webvortex_itinerary_transport' => $itinerary['transport'],
                            'field_webvortex_itinerary_allow_multiple_facilities' => $itinerary['allowSelectionOfMultipleFacilities'],
                            'field_webvortex_itinerary_accommodation' => $itinerary['accommodation'],
                            'field_webvortex_itinerary_min_persons' => $itinerary['min_persons'],
                            'field_webvortex_itinerary_min_price' => $itinerary['min_price'],
                            'field_webvortex_cancellation_policy' => array(
                                'field_webvortex_policy_id' => $itinerary['cancellationPolicy']['id'],
                                'field_webvortex_policy_charge' => $itinerary['cancellationPolicy']['charge'],
                                'field_webvortex_policy_days_before_1' => $itinerary['cancellationPolicy']['days_before_1'],
                                'field_webvortex_policy_days_before_2' => $itinerary['cancellationPolicy']['days_before_2'],
                                'field_webvortex_policy_title' => $itinerary['cancellationPolicy']['title'],
                                'field_webvortex_policy_description' => $itinerary['cancellationPolicy']['description']
                            ),
                            'field_webvortex_itinerary_is_event' => $itinerary['isEvent'],
                            'field_webvortex_itinerary_difficulty' => $itinerary['difficulty'],
                            'field_webvortex_itinerary_latitude' => $itinerary['latitude'],
                            'field_webvortex_itinerary_longitude' => $itinerary['longitude'],
                            'field_webvortex_itinerary_duration' => $itinerary['duration']
                        );
                    }
                    update_field('field_webvortex_itineraries', $itineraries, $post_id);
                }

                // Increment activities imported count
                $activities_imported++;
            }
        }
    }

    update_option('activities_imported', $activities_imported);
    echo "Import completed. Total activities imported: $activities_imported";
}

add_filter('template_include', 'unlimited_andrenaline_load_activity_template');
function unlimited_andrenaline_load_activity_template($template)
{
    if (is_singular('activity')) {
        $custom_template = plugin_dir_path(__FILE__) . 'single-activity.php';
        if (file_exists($custom_template)) {
            return $custom_template;
        }
    }
    return $template;
}
add_action('wp_ajax_get_itinerary_availability', 'get_itinerary_availability');
add_action('wp_ajax_nopriv_get_itinerary_availability', 'get_itinerary_availability');

function get_itinerary_availability()
{
    $itinerary_id = sanitize_text_field($_POST['itinerary_id']);
    $start_date = sanitize_text_field($_POST['start_date']);
    $end_date = sanitize_text_field($_POST['end_date']);

    $api_host = get_option('activity_host_url');
    $api_key = get_option('activity_api_key');

    $response = wp_remote_get(
        "$api_host/availability?itineraryId=$itinerary_id&startDate=$start_date&endDate=$end_date",
        array(
            'timeout' => 10,
            'headers' => array(
                'Accept' => 'application/json',
                "Authorization" => "Bearer $api_key"
            )
        )
    );

    if (is_wp_error($response)) {
        wp_send_json_error('Unable to fetch availability.');
    }

    $body = wp_remote_retrieve_body($response);
    $availability = json_decode($body, true);

    if (empty($availability)) {
        wp_send_json_error('No availability data found.');
    }

    $unavailable_dates = array();
    $time_slots = array();

    foreach ($availability['dates'] as $date => $info) {
        if (empty($info['available'])) {
            $unavailable_dates[] = $date;
        } else {
            $time_slots[$date] = array_map(function ($slot) {
                return array('timeId' => $slot['timeId'], 'startTime' => $slot['startTime']);
            }, $info['availabilityTimes']);
        }
    }

    wp_send_json_success(array('unavailable_dates' => $unavailable_dates, 'time_slots' => $time_slots));
}

function get_country_flag_by_language($language)
{
    $api_url = 'https://restcountries.com/v3.1/lang/' . urlencode($language);
    $response = file_get_contents($api_url);
    if ($response === FALSE) {
        return null;
    }
    $countries = json_decode($response, true);
    if (empty($countries)) {
        return null;
    }
    return $countries[0]['flags']['png'];
}


add_action('wp_ajax_get_availability', 'get_availability');
add_action('wp_ajax_nopriv_get_availability', 'get_availability');

function get_availability()
{
    if (!isset($_POST['itinerary_id'])) {
        wp_send_json_error('Missing itinerary ID.');
    }

    $itinerary_id = sanitize_text_field($_POST['itinerary_id']);
    $now = date('Y-m-d');
    $later = date('Y-m-d', strtotime('+30 days'));

    $availability = unlimited_adrenaline_remote_call(
        'get',
        "availability",
        array(
            "itineraryId" => $itinerary_id,
            "startDate" => $now,
            "endDate" => $later,
        ),
        []
    );

    if (empty($availability)) {
        wp_send_json_error('No availability data found.');
    }

    wp_send_json_success($availability);
}

add_action('wp_ajax_get_latest_pricing', 'get_latest_pricing');
add_action('wp_ajax_nopriv_get_latest_pricing', 'get_latest_pricing');

function get_latest_pricing()
{
    if (!isset($_POST['date']) || !isset($_POST['itinerary_id']) || !isset($_POST['time_slot'])) {
        wp_send_json_error('Missing parameters.');
    }

    $itinerary_id = sanitize_text_field($_POST['itinerary_id']);
    $person_count = sanitize_text_field($_POST['person_count']);
    $facilities = isset($_POST['facilities']) ? array_map('sanitize_text_field', $_POST['facilities']) : array();
    $selected_date = sanitize_text_field($_POST['date']);
    $selected_time_slot = sanitize_text_field($_POST['time_slot']);

    $pricing = unlimited_adrenaline_remote_call(
        'get',
        "getPricing",
        array(
            'itineraryId' => (int) $itinerary_id,
            'persons' => (int) $person_count,
            'facilityIds' => $facilities,
            'date' => $selected_date,
            'timeId' => (int) $selected_time_slot,
            'specialOffers' => array(),
        ),
        null
    );

    if (empty($pricing)) {
        wp_send_json_error('No pricing data found.');
    }

    wp_send_json_success($pricing);
}

add_action('wp_ajax_checkout', 'checkout');
add_action('wp_ajax_nopriv_checkout', 'checkout');

function checkout()
{
    if (!isset($_POST['itinerary_id']) || !isset($_POST['person_count']) || !isset($_POST['customer_details'])) {
        wp_send_json_error('Missing parameters.');
    }

    $itinerary_id = sanitize_text_field($_POST['itinerary_id']);
    $person_count = sanitize_text_field($_POST['person_count']);
    $facilities = isset($_POST['facilities']) ? array_map('sanitize_text_field', $_POST['facilities']) : array();
    $selected_date = sanitize_text_field($_POST['date']);
    $selected_time_slot = sanitize_text_field($_POST['time_slot']);
    $customer_details = array_map('sanitize_text_field', $_POST['customer_details']);

    $addToCartResponse = unlimited_adrenaline_remote_call(
        'post',
        "cart",
        array(
            'itineraryId' => (int) $itinerary_id,
            'persons' => (int) $person_count,
            'facilityIds' => $facilities,
            'date' => $selected_date,
            'timeId' => (int) $selected_time_slot,
            'specialOffers' => array(), // Special offers are not supported yet
        ),
        null
    );

    if (empty($addToCartResponse)) {
        wp_send_json_error('Failed to add to cart.');
    }

    $checkoutResponse = unlimited_adrenaline_remote_call(
        'post',
        "checkout",
        array(
            'cartReference' => $addToCartResponse['cartReference'],
            "name" => $customer_details['name'], 
            "surname" => $customer_details['surname'],
            "email" => $customer_details['email'],
            "phone" => $customer_details['phone'],
            "countryCode" => 'GR',
            'failUrl' => get_option('activity_api_ok_host'),
            'successUrl' => get_option('activity_api_ok_host'),
        ),
        null
    );

    if (empty($checkoutResponse)) {
        wp_send_json_error('Failed to checkout.');
    }

    wp_send_json_success(array('paymentUrl' => $checkoutResponse['paymentUrl']));
}

function unlimited_adrenaline_enqueue_styles_scripts()
{
    wp_enqueue_style('flatpickr-styles', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css');
    wp_enqueue_script('flatpickr-script', 'https://cdn.jsdelivr.net/npm/flatpickr', array(), null, true);
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-dialog');
    wp_enqueue_style('jquery-ui-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
}
add_action('wp_enqueue_scripts', 'unlimited_adrenaline_enqueue_styles_scripts');

function unlimited_adrenaline_remote_call($method, $api_request, $body, $default = null)
{
    $api_locale = get_option('activity_api_locale');
    $api_host = get_option('activity_host_url');
    $api_key = get_option('activity_api_key');

    if ($method === 'get') {
        $json_data = wp_json_encode($body);

        $url = $api_host . $api_request;

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($json_data),
                'Accept: application/json',
                'apiKey: ' . $api_key,
                'Accept-Language: ' . $api_locale,
            )
        );
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

        $response = curl_exec($ch);

        return json_decode($response, true);
    } elseif ($method === 'post') {
        $response = wp_remote_post(
            $api_host . $api_request,
            array(
                'body' => wp_json_encode($body),
                'timeout' => 10,
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'apiKey' => $api_key,
                    'Accept-Language' => $api_locale,
                ),
                // 'data_format' => 'body',
            )
        );

        if (is_wp_error($response)) {
            return $default;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (empty($data)) {
            return $default;
        }

        return $data;
    } else {
        return $default;
    }
}

function _log($msg, $stdout = true)
{
    if ($stdout) {
        $prefix = "WP - " . date("c") . "\n\n";

        $log = print_r($msg, true);

        $out = fopen('php://stdout', 'w');
        fputs($out, $prefix);

        fputs($out, $log);

        fputs($out, "\n\n");

        fclose($out);
    } else {
        print_r($msg);
    }
}

?>