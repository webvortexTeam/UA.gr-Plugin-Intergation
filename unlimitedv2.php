<?php
/*
Plugin Name: Unlimited Adrenaline V2
Plugin URI: https://github.com/webvortexTeam/UA.gr-Plugin-Intergation
Description: API διασύνδεση με τις δραστηριότητες της Unlimited Andrenaline
Version: 1.1.1
Author: WebVortex
Author URI: https://www.webvortex.org
Text Domain: unlimited-adrenaline-v2
Domain Path: /languages
License: GPL-2.0+
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('Requests')) {
    require_once ABSPATH . WPINC . '/class-requests.php';
}


define('MY_PLUGIN_DIR_PATH', untrailingslashit(plugin_dir_path(__FILE__)));

require_once MY_PLUGIN_DIR_PATH . '/class-tgm-plugin-activation.php';
require_once MY_PLUGIN_DIR_PATH . '/includes/unlimitedv2-class-permalink-issue.php'; // permalink issue

require_once MY_PLUGIN_DIR_PATH . '/inc/admin-settings.php';
require_once MY_PLUGIN_DIR_PATH . '/inc/admin-style.php';

add_action('tgmpa_register', 'unlimited_andrenaline_v2_register_required_plugins');

function unlimited_andrenaline_v2_register_required_plugins()
{
    $plugins = array(
        array(
            'name' => 'Advanced Custom Fields PRO', // The plugin name.
            'slug' => 'advanced-custom-fields-pro', // The plugin slug (typically the folder name).
            'source' => 'https://api.webvortex.cloud/advanced-custom-fields-pro.zip', // The plugin source.
            'required' => true, // If false, the plugin is only 'recommended' instead of required.
            'external_url' => 'https://api.webvortex.cloud/advanced-custom-fields-pro.zip', // If set, overrides default API URL and points to an external URL.
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

require_once MY_PLUGIN_DIR_PATH . '/includes/unlimitedv2-class-custom-post-type.php'; // Custom Post για τις δραστηριότητες

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

require_once MY_PLUGIN_DIR_PATH . '/inc/import-page.php'; // Backend διαχείριση import

require_once MY_PLUGIN_DIR_PATH . '/includes/unlimitedv2-class-import-activities.php'; // Custom Post για τις δραστηριότητες

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

require_once MY_PLUGIN_DIR_PATH . '/includes/unlimitedv2-class-get-itinerary-avail.php'; // Διαθεσιμότητα

require_once MY_PLUGIN_DIR_PATH . '/includes/unlimitedv2-class-get-flag.php'; // Get εικόνες χωρών απο εξωτερικό free API



add_action('wp_ajax_get_availability', 'get_availability');
add_action('wp_ajax_nopriv_get_availability', 'get_availability');

require_once MY_PLUGIN_DIR_PATH . '/includes/unlimitedv2-class-get-avail.php'; // διαθεσιμότητα ημερολογίου

add_action('wp_ajax_get_latest_pricing', 'get_latest_pricing');
add_action('wp_ajax_nopriv_get_latest_pricing', 'get_latest_pricing');

require_once MY_PLUGIN_DIR_PATH . '/includes/unlimitedv2-class-get-latest-pricing.php'; // Τιμολόγηση


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
            'specialOffers' => array(), // Special offers δεν υποστηρίζονται ακόμη.
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
            'failUrl' => get_option('activity_api_fail_host'),
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

require_once MY_PLUGIN_DIR_PATH . '/includes/unlimitedv2-class-api-calls-structure.php'; // Δομή api call


require_once MY_PLUGIN_DIR_PATH . '/includes/unlimitedv2-class-log.php'; // Debug

if( ! class_exists( 'vortexUpdateChecker' ) ) {

	class vortexUpdateChecker{

		public $plugin_slug;
		public $version;
		public $cache_key;
		public $cache_allowed;

		public function __construct() {

			$this->plugin_slug = plugin_basename( __DIR__ );
			$this->version = '1.0';
			$this->cache_key = '9r3u104910hrj13r1309rh13r490149u12';
			$this->cache_allowed = false;

			add_filter( 'plugins_api', array( $this, 'info' ), 20, 3 );
			add_filter( 'site_transient_update_plugins', array( $this, 'update' ) );
			add_action( 'upgrader_process_complete', array( $this, 'purge' ), 10, 2 );

		}

		public function request(){

			$remote = get_transient( $this->cache_key );

			if( false === $remote || ! $this->cache_allowed ) {

				$remote = wp_remote_get(
					'https://webvortexteam.github.io/UA.gr-Plugin-Intergation/updater.json',
					array(
						'timeout' => 10,
						'headers' => array(
							'Accept' => 'application/json'
						)
					)
				);

				if(
					is_wp_error( $remote )
					|| 200 !== wp_remote_retrieve_response_code( $remote )
					|| empty( wp_remote_retrieve_body( $remote ) )
				) {
					return false;
				}

				set_transient( $this->cache_key, $remote, DAY_IN_SECONDS );

			}

			$remote = json_decode( wp_remote_retrieve_body( $remote ) );

			return $remote;

		}


		function info( $res, $action, $args ) {

			// print_r( $action );
			// print_r( $args );

			// do nothing if you're not getting plugin information right now
			if( 'plugin_information' !== $action ) {
				return $res;
			}

			// do nothing if it is not our plugin
			if( $this->plugin_slug !== $args->slug ) {
				return $res;
			}

			// get updates
			$remote = $this->request();

			if( ! $remote ) {
				return $res;
			}

			$res = new stdClass();

			$res->name = $remote->name;
			$res->slug = $remote->slug;
			$res->version = $remote->version;
			$res->tested = $remote->tested;
			$res->requires = $remote->requires;
			$res->author = $remote->author;
			$res->author_profile = $remote->author_profile;
			$res->download_link = $remote->download_url;
			$res->trunk = $remote->download_url;
			$res->requires_php = $remote->requires_php;
			$res->last_updated = $remote->last_updated;

			$res->sections = array(
				'description' => $remote->sections->description,
				'installation' => $remote->sections->installation,
				'changelog' => $remote->sections->changelog
			);

			if( ! empty( $remote->banners ) ) {
				$res->banners = array(
					'low' => $remote->banners->low,
					'high' => $remote->banners->high
				);
			}

			return $res;

		}

		public function update( $transient ) {

			if ( empty($transient->checked ) ) {
				return $transient;
			}

			$remote = $this->request();

			if(
				$remote
				&& version_compare( $this->version, $remote->version, '<' )
				&& version_compare( $remote->requires, get_bloginfo( 'version' ), '<=' )
				&& version_compare( $remote->requires_php, PHP_VERSION, '<' )
			) {
				$res = new stdClass();
				$res->slug = $this->plugin_slug;
				$res->plugin = plugin_basename( __FILE__ ); // webvortex-update-plugin/webvortex-update-plugin.php
				$res->new_version = $remote->version;
				$res->tested = $remote->tested;
				$res->package = $remote->download_url;

				$transient->response[ $res->plugin ] = $res;

	    }

			return $transient;

		}

		public function purge( $upgrader, $options ){

			if (
				$this->cache_allowed
				&& 'update' === $options['action']
				&& 'plugin' === $options[ 'type' ]
			) {
				// just clean the cache when new plugin version is installed
				delete_transient( $this->cache_key );
			}

		}


	}

	new vortexUpdateChecker();

}
function adrenaline_create_pages() {
    // Array of pages to create
    $pages = [
        [
            'title' => 'Thank You Adrenaline',
            'slug' => 'activity-booking-confirmed',
            'content' => adrenaline_thank_you_template()
        ],
        [
            'title' => 'Failed Adrenaline',
            'slug' => 'activity-booking-failed',
            'content' => adrenaline_failed_template()
        ]
    ];

    foreach ($pages as $page) {
        $page_check = get_page_by_path($page['slug']);
        if (!isset($page_check->ID)) {
            $new_page = [
                'post_title' => $page['title'],
                'post_name' => $page['slug'],
                'post_content' => $page['content'],
                'post_status' => 'publish',
                'post_type' => 'page',
            ];

            wp_insert_post($new_page);
        }
    }
}

register_activation_hook(__FILE__, 'adrenaline_create_pages');
require_once MY_PLUGIN_DIR_PATH . '/includes/unlimitedv2-class-payment-pages.php'; // Payment σελίδες thank-you - failed
require_once MY_PLUGIN_DIR_PATH . '/inc/admin-cron.php';
add_action('init', function() {
    register_activation_hook(__FILE__, 'uac_cron_activation');
    register_deactivation_hook(__FILE__, 'uac_cron_deactivation');
});
require_once MY_PLUGIN_DIR_PATH . '/includes/unlimitedv2-class-price-cheapest.php';

function use_custom_activity_template($template) {
    if (is_post_type_archive('activity')) {
        $plugin_template = plugin_dir_path(__FILE__) . 'archive-activity.php';
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }
    }
    return $template;
}
add_filter('archive_template', 'use_custom_activity_template');
