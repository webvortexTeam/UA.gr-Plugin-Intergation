<?php
    if (!defined('ABSPATH')) {
        exit; // Exit if accessed directly.
    }
    function get_availability() {
        if (!isset($_POST['itinerary_id'])) {
            wp_send_json_error('Missing itinerary_id parameter.');
        }
    
        $itinerary_id = sanitize_text_field($_POST['itinerary_id']);
        
        date_default_timezone_set('Europe/Athens'); // Greece
        $current_time = date('H:i');
        $start_date = isset($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) . ' ' . $current_time : date('Y-m-d') . ' ' . $current_time;
        $end_date = isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) . ' 23:59' : date('Y-m-d', strtotime('+30 days')) . ' 23:59';
    
        $availability = unlimited_adrenaline_remote_call(
            'get',
            'availability',
            array(
                'itineraryId' => $itinerary_id,
                'startDate' => $start_date,
                'endDate' => $end_date,
            ),
            []
        );
    
        if (empty($availability)) {
            wp_send_json_error('No availability data found.');
        }
    
        wp_send_json_success($availability);
    }
    
    add_action('wp_ajax_get_availability', 'get_availability');
    add_action('wp_ajax_nopriv_get_availability', 'get_availability');
?>
