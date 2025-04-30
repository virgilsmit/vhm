<?php
/**
 * Plugin Name:     Hardloop Manager
 * Plugin URI:      https://example.com
 * Description:     Beheer hardlopers, schema’s en import vanuit oude GAC-database.
 * Author:          Jouw Naam
 * Author URI:      https://example.com
 * Text Domain:     hardloop-manager
 * Domain Path:     /languages
 * Version:         0.1.1
 *
 * @package         Hardloop_Manager
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


/**
 * Register Custom Post Type "Loper" and Taxonomy "Groep"
 */
add_action( 'init', function() {
    // CPT labels
    $labels = [
        'name'                  => _x( 'Lopers', 'Post Type General Name', 'hardloop-manager' ),
        'singular_name'         => _x( 'Loper', 'Post Type Singular Name', 'hardloop-manager' ),
        'menu_name'             => __( 'Lopers', 'hardloop-manager' ),
        'name_admin_bar'        => __( 'Loper', 'hardloop-manager' ),
        'add_new'               => __( 'Add New', 'hardloop-manager' ),
        'add_new_item'          => __( 'Add New Loper', 'hardloop-manager' ),
        'edit_item'             => __( 'Edit Loper', 'hardloop-manager' ),
        'new_item'              => __( 'New Loper', 'hardloop-manager' ),
        'view_item'             => __( 'View Loper', 'hardloop-manager' ),
        'search_items'          => __( 'Search Lopers', 'hardloop-manager' ),
        'not_found'             => __( 'No lopers found', 'hardloop-manager' ),
        'not_found_in_trash'    => __( 'No lopers found in Trash', 'hardloop-manager' ),
        'all_items'             => __( 'All Lopers', 'hardloop-manager' ),
        'archives'              => __( 'Loper Archives', 'hardloop-manager' ),
        'insert_into_item'      => __( 'Insert into loper', 'hardloop-manager' ),
        'uploaded_to_this_item' => __( 'Uploaded to this loper', 'hardloop-manager' ),
    ];

    $args = [
        'label'               => __( 'Loper', 'hardloop-manager' ),
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => true,
        'show_in_rest'        => true,
        'menu_icon'           => 'dashicons-running',
        'supports'            => [ 'title', 'editor', 'thumbnail' ],
        'capability_type'     => [ 'loper', 'lopers' ],
        'map_meta_cap'        => true,
    ];
    register_post_type( 'loper', $args );

    // Taxonomy labels
    $tax_labels = [
        'name'              => _x( 'Groepen', 'taxonomy general name', 'hardloop-manager' ),
        'singular_name'     => _x( 'Groep', 'taxonomy singular name', 'hardloop-manager' ),
        'search_items'      => __( 'Search Groepen', 'hardloop-manager' ),
        'all_items'         => __( 'All Groepen', 'hardloop-manager' ),
        'parent_item'       => __( 'Parent Groep', 'hardloop-manager' ),
        'edit_item'         => __( 'Edit Groep', 'hardloop-manager' ),
        'update_item'       => __( 'Update Groep', 'hardloop-manager' ),
        'add_new_item'      => __( 'Add New Groep', 'hardloop-manager' ),
        'new_item_name'     => __( 'New Groep Name', 'hardloop-manager' ),
        'menu_name'         => __( 'Groepen', 'hardloop-manager' ),
    ];

    $tax_args = [
        'hierarchical'      => true,
        'labels'            => $tax_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => true,
        'rewrite'           => [ 'slug' => 'groep' ],
    ];
    register_taxonomy( 'groep', [ 'loper' ], $tax_args );
}, 0 );


/**
 * Register ACF fields for CPT "loper" via code.
 */
add_action( 'acf/init', function() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    acf_add_local_field_group( array(
        'key'      => 'group_loper_gegevens',
        'title'    => 'Loper Gegevens',
        'fields'   => array(
            array(
                'key'   => 'field_voornaam',
                'label' => 'Voornaam',
                'name'  => 'voornaam',
                'type'  => 'text',
            ),
            array(
                'key'   => 'field_achternaam',
                'label' => 'Achternaam',
                'name'  => 'achternaam',
                'type'  => 'text',
            ),
            array(
                'key'   => 'field_email',
                'label' => 'E-mail',
                'name'  => 'email',
                'type'  => 'email',
            ),
            array(
                'key'            => 'field_geboortedatum',
                'label'          => 'Geboortedatum',
                'name'           => 'geboortedatum',
                'type'           => 'date_picker',
                'display_format' => 'd-m-Y',
                'return_format'  => 'd-m-Y',
            ),
            array(
                'key'   => 'field_noodnummer',
                'label' => 'Noodnummer',
                'name'  => 'noodnummer',
                'type'  => 'text',
            ),
            array(
                'key'   => 'field_loopervaring',
                'label' => 'Loopervaring',
                'name'  => 'loopervaring',
                'type'  => 'textarea',
            ),
            array(
                'key'   => 'field_gewenst_pace',
                'label' => 'Gewenst pace',
                'name'  => 'gewenst_pace',
                'type'  => 'text',
            ),
            array(
                'key'   => 'field_doel_2025',
                'label' => 'Doel 2025',
                'name'  => 'doel_2025',
                'type'  => 'textarea',
            ),
            array(
                'key'   => 'field_shirt',
                'label' => 'Shirtmaat',
                'name'  => 'shirt',
                'type'  => 'text',
            ),
            array(
                'key'   => 'field_blessures',
                'label' => 'Blessures',
                'name'  => 'blessures',
                'type'  => 'textarea',
            ),
            array(
                'key'   => 'field_opmerkingen',
                'label' => 'Opmerkingen',
                'name'  => 'opmerkingen',
                'type'  => 'textarea',
            ),
            array(
                'key'   => 'field_trainer_opmerkingen',
                'label' => 'Trainer opmerkingen',
                'name'  => 'trainer_opmerkingen',
                'type'  => 'textarea',
            ),
            array(
                'key'   => 'field_niveau',
                'label' => 'Niveau',
                'name'  => 'niveau',
                'type'  => 'text',
            ),
            array(
                'key'   => 'field_actief',
                'label' => 'Actief',
                'name'  => 'actief',
                'type'  => 'true_false',
                'ui'    => 1,
            ),
            array(
                'key'            => 'field_gestart',
                'label'          => 'Gestart op',
                'name'           => 'gestart',
                'type'           => 'date_picker',
                'display_format' => 'd-m-Y',
                'return_format'  => 'd-m-Y',
            ),
            array(
                'key'            => 'field_gestopt',
                'label'          => 'Gestopt op',
                'name'           => 'gestopt',
                'type'           => 'date_picker',
                'display_format' => 'd-m-Y',
                'return_format'  => 'd-m-Y',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'loper',
                ),
            ),
        ),
    ) );
}, 20 );