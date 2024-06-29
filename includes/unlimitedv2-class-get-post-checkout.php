<?php
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