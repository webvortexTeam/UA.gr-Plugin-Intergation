<?php
    if (!defined('ABSPATH')) {
        exit; // Exit if accessed directly.
    }
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