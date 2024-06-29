<?php
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
