<?php
    if (!defined('ABSPATH')) {
        exit; // Exit if accessed directly.
    }
function ua_save_cheapest_price($post_id) {
    if (get_post_type($post_id) !== 'activity') {
        return;
    }

    if (have_rows('itineraries', $post_id)) {
        $cheapest_price = null;

        while (have_rows('itineraries', $post_id)) {
            the_row();
            $min_price = get_sub_field('min_price');

            if ($min_price !== null && ($cheapest_price === null || $min_price < $cheapest_price)) {
                $cheapest_price = $min_price;
            }
        }

        if ($cheapest_price !== null) {
            update_field('cheapest_price', $cheapest_price, $post_id);
        }
    }
}

add_action('save_post', 'ua_save_cheapest_price');
