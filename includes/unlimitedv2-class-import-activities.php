<?php
    if (!defined('ABSPATH')) {
        exit; // Exit if accessed directly.
    }
function set_featured_image_from_url($image_url, $post_id) {
    $upload_dir = wp_upload_dir();
    $image_data = file_get_contents($image_url);
    $filename = basename($image_url);
    
    if (wp_mkdir_p($upload_dir['path'])) {
        $file = $upload_dir['path'] . '/' . $filename;
    } else {
        $file = $upload_dir['basedir'] . '/' . $filename;
    }

    file_put_contents($file, $image_data);

    $wp_filetype = wp_check_filetype($filename, null);
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_file_name($filename),
        'post_content' => '',
        'post_status' => 'inherit'
    );

    $attach_id = wp_insert_attachment($attachment, $file, $post_id);
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata($attach_id, $file);
    wp_update_attachment_metadata($attach_id, $attach_data);
    set_post_thumbnail($post_id, $attach_id);
}

function unlimited_andrenaline_import_activities() {
    $batch_size = 5; // Number of activities to process per batch
    $batch = isset($_POST['batch']) ? intval($_POST['batch']) : 0;

    $whitelabelid = get_option('activity_host_url_label');
    $api_host = get_option('activity_host_url');
    $api_key = get_option('activity_api_key');
    $api_locale = get_option('activity_api_locale');

    // Fetch categories
    $categories_url = $api_host . 'category';
    $categories_args = array(
        'headers' => array(
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'apiKey' => $api_key,
            'Accept-Language' => $api_locale
        )
    );

    $categories_response = wp_remote_get($categories_url, $categories_args);
    if (is_wp_error($categories_response)) {
        wp_send_json_error(array('message' => 'Category API error: ' . $categories_response->get_error_message()));
    }

    $categories_body = wp_remote_retrieve_body($categories_response);
    $categories_data = json_decode($categories_body, true);

    // Create a mapping of subcategory IDs to subcategory names
    $subcategory_map = array();
    foreach ($categories_data as $category) {
        foreach ($category['subcategories'] as $subcategory) {
            $subcategory_map[$subcategory['id']] = $subcategory['title'];
        }
    }

    $endpoint = "activity";
    $url = add_query_arg(array('whiteLabelId' => $whitelabelid), $api_host . $endpoint);
    $args = array(
        'headers' => array(
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'apiKey' => $api_key,
            'Accept-Language' => $api_locale
        )
    );

    $response = wp_remote_get($url, $args);
    if (is_wp_error($response)) {
        wp_send_json_error(array('message' => 'API error: ' . $response->get_error_message()));
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);
    if (empty($data)) {
        wp_send_json_error(array('message' => 'No activities found'));
    }

    $total_activities = count($data);
    $total_batches = ceil($total_activities / $batch_size);
    $activities_batch = array_slice($data, $batch * $batch_size, $batch_size);

    // Get existing activities to determine which ones to delete
        $existing_activities = get_posts(array(
            'post_type' => 'activity',
            'numberposts' => -1,
            'fields' => 'ids'
        ));

        $api_activity_ids = array_column($data, 'id');


    foreach ($activities_batch as $activity) {
        $activity_title = $activity['title'];
        $post_id = null;

        // Check if the activity already exists
        $existing_post = get_posts(array(
            'post_type' => 'activity',
            'meta_key' => 'field_webvortex_activity_id',
            'meta_value' => $activity['id'],
            'posts_per_page' => 1,
        ));

        if ($existing_post) {
            $post_id = $existing_post[0]->ID;
            wp_update_post(array(
                'ID' => $post_id,
                'post_title' => $activity_title,
            ));
        } else {
            $post_id = wp_insert_post(array(
                'post_title' => $activity_title,
                'post_type' => 'activity',
                'post_status' => 'publish'
            ));
        }

        if ($post_id && !is_wp_error($post_id)) {
            update_field('field_webvortex_activity_id', $activity['id'], $post_id);
            update_field('field_webvortex_title', $activity['title'], $post_id);
            update_field('field_webvortex_provider_id', $activity['providerId'], $post_id);
            update_field('field_webvortex_rating', $activity['rating'], $post_id);
            update_field('field_webvortex_active_months', $activity['activeMonths'], $post_id);
            update_field('field_webvortex_category_ids', implode(', ', $activity['categoryIds']), $post_id);
            update_field('field_webvortex_description', $activity['description'], $post_id);

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

                $first_image_url = $activity['photos'][0]['full'];
                set_featured_image_from_url($first_image_url, $post_id);
            }

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
                        'field_webvortex_itinerary_title' => $itinerary_title,
                        'field_webvortex_itinerary_description' => $itinerary['description'],
                        'field_webvortex_itinerary_location_text' => $itinerary['meetingPointArea'],
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
                        //new august final
                        'meetingPointArea' => $itinerary['meetingPointArea'],
                        'meetingPointAdress' => $itinerary['meetingPointAdress'],
                        'notes' => $itinerary['notes'],
                        'activityArea' => $itinerary['activityArea'],
                        // end new aust final
                        'field_webvortex_itinerary_cancellation_policy' => array(
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

                    if (!empty($itinerary['meetingPointArea'])) {
                        $location_term = term_exists($itinerary['meetingPointArea'], 'activity_location');
                        if (!$location_term) {
                            $location_term = wp_insert_term($itinerary['meetingPointArea'], 'activity_location');
                        }
                        if (!is_wp_error($location_term)) {
                            wp_set_post_terms($post_id, array(intval($location_term['term_id'])), 'activity_location', true);
                        }
                    }
                }
                update_field('field_webvortex_itineraries', $itineraries, $post_id);
            }

            $activity_categories = array();
            foreach ($activity['categoryIds'] as $category_id) {
                if (isset($subcategory_map[$category_id])) {
                    $term = term_exists($subcategory_map[$category_id], 'activity_category');
                    if (!$term) {
                        $term = wp_insert_term($subcategory_map[$category_id], 'activity_category');
                    }
                    if (!is_wp_error($term)) {
                        $activity_categories[] = intval($term['term_id']);
                    }
                }
            }
            wp_set_post_terms($post_id, $activity_categories, 'activity_category');
        }
    }

    $next_batch = ($batch + 1 < $total_batches) ? $batch + 1 : null;
    $progress = round((($batch + 1) / $total_batches) * 100);

    wp_send_json_success(array('progress' => $progress, 'next_batch' => $next_batch));
}

// Register AJAX actions
add_action('wp_ajax_unlimited_andrenaline_import_activities', 'unlimited_andrenaline_import_activities');
add_action('wp_ajax_nopriv_unlimited_andrenaline_import_activities', 'unlimited_andrenaline_import_activities');




function create_activity_category_taxonomy() {
    register_taxonomy(
        'activity_category',
        'activity',
        array(
            'label' => __( 'Activity Categories' ),
            'rewrite' => array( 'slug' => 'activity-category' ),
            'hierarchical' => true,
        )
    );
}
add_action( 'init', 'create_activity_category_taxonomy' );


function create_activity_location_taxonomy() {
    register_taxonomy(
        'activity_location',
        'activity',
        array(
            'label' => __( 'Activity Location' ),
            'rewrite' => array( 'slug' => 'activity-location' ),
            'hierarchical' => true,
        )
    );
}
add_action( 'init', 'create_activity_location_taxonomy' );
function delete_empty_activity_terms() {
    $taxonomies = array('activity_category', 'activity_location');

    foreach ($taxonomies as $taxonomy) {
        $terms = get_terms(array(
            'taxonomy' => $taxonomy,
            'hide_empty' => false, // Get all terms, even those with no posts
        ));

        foreach ($terms as $term) {
            $count = get_term_post_count($term->term_id, $taxonomy);
            if ($count == 0) {
                wp_delete_term($term->term_id, $taxonomy);
            }
        }
    }
}

function get_term_post_count($term_id, $taxonomy) {
    global $wpdb;

    $query = $wpdb->prepare(
        "SELECT COUNT(*) FROM $wpdb->term_relationships
        WHERE term_taxonomy_id = %d",
        $term_id
    );
    
    return $wpdb->get_var($query);
}
?>
