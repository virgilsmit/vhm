<?php
/**
 * Plugin Name:     Hardloop Manager
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     PLUGIN DESCRIPTION HERE
 * Author:          YOUR NAME HERE
 * Author URI:      YOUR SITE HERE
 * Text Domain:     hardloop-manager
 * Domain Path:     /languages
 * Version:         0.1.1
 *
 * @package         Hardloop_Manager
 */

// Your code starts here.
require_once __DIR__ . '/src/acf.php';

add_action('init', function() {
    // Register Custom Post Type: loper
    $labels = [
        'name'                  => _x('Lopers', 'Post Type General Name', 'hardloop-manager'),
        'singular_name'         => _x('Loper', 'Post Type Singular Name', 'hardloop-manager'),
        'menu_name'             => __('Lopers', 'hardloop-manager'),
        'name_admin_bar'        => __('Loper', 'hardloop-manager'),
        'add_new'               => __('Add New', 'hardloop-manager'),
        'add_new_item'          => __('Add New Loper', 'hardloop-manager'),
        'edit_item'             => __('Edit Loper', 'hardloop-manager'),
        'new_item'              => __('New Loper', 'hardloop-manager'),
        'view_item'             => __('View Loper', 'hardloop-manager'),
        'search_items'          => __('Search Lopers', 'hardloop-manager'),
        'not_found'             => __('No lopers found', 'hardloop-manager'),
        'not_found_in_trash'    => __('No lopers found in Trash', 'hardloop-manager'),
        'all_items'             => __('All Lopers', 'hardloop-manager'),
        'archives'              => __('Loper Archives', 'hardloop-manager'),
        'insert_into_item'      => __('Insert into loper', 'hardloop-manager'),
        'uploaded_to_this_item' => __('Uploaded to this loper', 'hardloop-manager'),
    ];

    $args = [
        'label'               => __('Loper', 'hardloop-manager'),
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => true,
        'show_in_rest'        => true,
        'menu_icon'           => 'dashicons-running',
        'supports'            => ['title', 'editor', 'thumbnail'],
        'capability_type'     => ['loper', 'lopers'],
        'map_meta_cap'        => true,
    ];
    register_post_type('loper', $args);

    // Register Taxonomy: groep
    $tax_labels = [
        'name'              => _x('Groepen', 'taxonomy general name', 'hardloop-manager'),
        'singular_name'     => _x('Groep', 'taxonomy singular name', 'hardloop-manager'),
        'search_items'      => __('Search Groepen', 'hardloop-manager'),
        'all_items'         => __('All Groepen', 'hardloop-manager'),
        'parent_item'       => __('Parent Groep', 'hardloop-manager'),
        'parent_item_colon' => __('Parent Groep:', 'hardloop-manager'),
        'edit_item'         => __('Edit Groep', 'hardloop-manager'),
        'update_item'       => __('Update Groep', 'hardloop-manager'),
        'add_new_item'      => __('Add New Groep', 'hardloop-manager'),
        'new_item_name'     => __('New Groep Name', 'hardloop-manager'),
        'menu_name'         => __('Groepen', 'hardloop-manager'),
    ];

    $tax_args = [
        'hierarchical'      => true,
        'labels'            => $tax_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => true,
        'rewrite'           => ['slug' => 'groep'],
    ];
    register_taxonomy('groep', ['loper'], $tax_args);
});
