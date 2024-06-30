<?php
    if (!defined('ABSPATH')) {
        exit; // Exit if accessed directly.
    }
function set_permalink_structure_to_postname() {
    $desired_permalink_structure = '/%postname%/';
    
    $current_permalink_structure = get_option('permalink_structure');
    
    if ($current_permalink_structure !== $desired_permalink_structure) {
        update_option('permalink_structure', $desired_permalink_structure);
    }
}
add_action('init', 'set_permalink_structure_to_postname');
