<?php
    if (!defined('ABSPATH')) {
        exit; // Exit if accessed directly.
    }function unlimited_andrenaline_v2_custom_post_type()
{
    $labels = array(
        'name' => _x('Activities', 'Post Type General Name', 'unlimited-andrenaline-v2'),
        'singular_name' => _x('Activity', 'Post Type Singular Name', 'unlimited-andrenaline-v2'),
        'menu_name' => __('Activities', 'unlimited-andrenaline-v2'),
        'name_admin_bar' => __('Activity', 'unlimited-andrenaline-v2'),
        'archives' => __('Item Archives', 'unlimited-andrenaline-v2'),
        'attributes' => __('Item Attributes', 'unlimited-andrenaline-v2'),
        'parent_item_colon' => __('Parent Item:', 'unlimited-andrenaline-v2'),
        'all_items' => __('All Activities', 'unlimited-andrenaline-v2'),
        'add_new_item' => __('Add New Item', 'unlimited-andrenaline-v2'),
        'add_new' => __('Add New', 'unlimited-andrenaline-v2'),
        'new_item' => __('New Item', 'unlimited-andrenaline-v2'),
        'edit_item' => __('Edit Item', 'unlimited-andrenaline-v2'),
        'update_item' => __('Update Item', 'unlimited-andrenaline-v2'),
        'view_item' => __('View Item', 'unlimited-andrenaline-v2'),
        'view_items' => __('View Items', 'unlimited-andrenaline-v2'),
        'search_items' => __('Search Item', 'unlimited-andrenaline-v2'),
        'not_found' => __('Not found', 'unlimited-andrenaline-v2'),
        'not_found_in_trash' => __('Not found in Trash', 'unlimited-andrenaline-v2'),
        'featured_image' => __('Featured Image', 'unlimited-andrenaline-v2'),
        'set_featured_image' => __('Set featured image', 'unlimited-andrenaline-v2'),
        'remove_featured_image' => __('Remove featured image', 'unlimited-andrenaline-v2'),
        'use_featured_image' => __('Use as featured image', 'unlimited-andrenaline-v2'),
        'insert_into_item' => __('Insert into item', 'unlimited-andrenaline-v2'),
        'uploaded_to_this_item' => __('Uploaded to this item', 'unlimited-andrenaline-v2'),
        'items_list' => __('Items list', 'unlimited-andrenaline-v2'),
        'items_list_navigation' => __('Items list navigation', 'unlimited-andrenaline-v2'),
        'filter_items_list' => __('Filter items list', 'unlimited-andrenaline-v2'),
    );
    $args = array(
        'label' => __('Activity', 'unlimited-andrenaline-v2'),
        'description' => __('Activity Description', 'unlimited-andrenaline-v2'),
        'labels' => $labels,
        'supports' => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        'taxonomies' => array('activity_category'), // category
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'post',
    );
    register_post_type('activity', $args);
}
add_action('init', 'unlimited_andrenaline_v2_custom_post_type', 0);

// Add custom columns to the Activity post type admin table
function set_custom_edit_activity_columns($columns) {
    $columns['activity_category'] = __('Activity Category', 'unlimited-andrenaline-v2');
    return $columns;
}
add_filter('manage_activity_posts_columns', 'set_custom_edit_activity_columns');

// Populate the custom columns with data
function custom_activity_column($column, $post_id) {
    switch ($column) {
        case 'activity_category':
            $terms = get_the_term_list($post_id, 'activity_category', '', ', ', '');
            if (is_string($terms)) {
                echo $terms;
            } else {
                _e('No Activity Category', 'unlimited-andrenaline-v2');
            }
            break;
    }
}
add_action('manage_activity_posts_custom_column', 'custom_activity_column', 10, 2);
