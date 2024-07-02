<?php
    if (!defined('ABSPATH')) {
        exit; // Exit if accessed directly.
    }
    <?php
    function get_availability() {
        if (!isset($_POST['itinerary_id'])) {
            wp_send_json_error('Missing itinerary_id parameter.');
        }
    
        $itinerary_id = sanitize_text_field($_POST['itinerary_id']);
        
        // Set default start and end dates
        $start_date = isset($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : date('Y-m-d');
        $end_date = isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : date('Y-m-d', strtotime('+30 days'));
    
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
    
    