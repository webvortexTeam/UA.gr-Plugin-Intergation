<?php
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