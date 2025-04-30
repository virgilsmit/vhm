<?php
/**
 * Plugin Name:     Hardloop Manager
 * Description:     Beheer hardlopers + import vanuit GAC_hardlopers.
 * Version:         0.1.2
 * Text Domain:     hardloop-manager
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Register CPT “loper” and taxonomy “groep”
add_action( 'init', function() {
    $labels = [
        'name'               => _x( 'Lopers', 'Post Type General Name', 'hardloop-manager' ),
        'singular_name'      => _x( 'Loper', 'Post Type Singular Name', 'hardloop-manager' ),
        'menu_name'          => __( 'Lopers', 'hardloop-manager' ),
        'add_new_item'       => __( 'Add New Loper', 'hardloop-manager' ),
        'edit_item'          => __( 'Edit Loper', 'hardloop-manager' ),
        'view_item'          => __( 'View Loper', 'hardloop-manager' ),
        'search_items'       => __( 'Search Lopers', 'hardloop-manager' ),
        'not_found'          => __( 'No lopers found', 'hardloop-manager' ),
        'not_found_in_trash' => __( 'No lopers in Trash', 'hardloop-manager' ),
    ];
    register_post_type( 'loper', [
        'labels'       => $labels,
        'public'       => true,
        'has_archive'  => true,
        'show_in_rest' => true,
        'menu_icon'    => 'dashicons-running',
        'supports'     => [ 'title', 'editor', 'thumbnail' ],
    ] );

    register_taxonomy( 'groep', 'loper', [
        'labels'       => [
            'name'          => __( 'Groepen', 'hardloop-manager' ),
            'singular_name' => __( 'Groep', 'hardloop-manager' ),
        ],
        'hierarchical' => true,
        'show_in_rest' => true,
    ] );
}, 0 );

// Register all ACF fields programmatically
add_action( 'acf/init', function() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

    acf_add_local_field_group( [
        'key'      => 'group_loper_all_fields',
        'title'    => 'Alle Loper Velden',
        'fields'   => [
            ['key'=>'field_voornaam','label'=>'Voornaam','name'=>'voornaam','type'=>'text'],
            ['key'=>'field_achternaam','label'=>'Achternaam','name'=>'achternaam','type'=>'text'],
            ['key'=>'field_email','label'=>'E-mail','name'=>'email','type'=>'email'],
            ['key'=>'field_geboortedatum','label'=>'Geboortedatum','name'=>'geboortedatum','type'=>'date_picker','display_format'=>'d-m-Y','return_format'=>'Y-m-d'],
            ['key'=>'field_noodnummer','label'=>'Noodnummer','name'=>'noodnummer','type'=>'text'],
            ['key'=>'field_loopervaring','label'=>'Loopervaring','name'=>'loopervaring','type'=>'textarea'],
            ['key'=>'field_gewenst_pace','label'=>'Gewenst pace','name'=>'gewenst_pace','type'=>'text'],
            ['key'=>'field_doel_2025','label'=>'Doel 2025','name'=>'doel_2025','type'=>'textarea'],
            ['key'=>'field_shirt','label'=>'Shirtmaat','name'=>'shirt','type'=>'text'],
            ['key'=>'field_blessures','label'=>'Blessures','name'=>'blessures','type'=>'textarea'],
            ['key'=>'field_opmerkingen','label'=>'Opmerkingen','name'=>'opmerkingen','type'=>'textarea'],
            ['key'=>'field_trainer_opmerkingen','label'=>'Trainer opmerkingen','name'=>'trainer_opmerkingen','type'=>'textarea'],
            ['key'=>'field_status','label'=>'Status','name'=>'status','type'=>'text'],
            ['key'=>'field_created_at','label'=>'Aangemaakt op','name'=>'created_at','type'=>'date_time_picker'],
            ['key'=>'field_updated_at','label'=>'Bijgewerkt op','name'=>'updated_at','type'=>'date_time_picker'],
            ['key'=>'field_password','label'=>'Password','name'=>'password','type'=>'text'],
            ['key'=>'field_trainer_email','label'=>'Trainer Email','name'=>'trainer_email','type'=>'email'],
            ['key'=>'field_actief','label'=>'Actief','name'=>'actief','type'=>'true_false','ui'=>1],
            ['key'=>'field_gestart','label'=>'Gestart op','name'=>'gestart','type'=>'date_picker','display_format'=>'d-m-Y','return_format'=>'Y-m-d'],
            ['key'=>'field_gestopt','label'=>'Gestopt op','name'=>'gestopt','type'=>'date_picker','display_format'=>'d-m-Y','return_format'=>'Y-m-d'],
        ],
        'location' => [
            [
                ['param'=>'post_type','operator'=>'==','value'=>'loper']
            ]
        ],
    ] );
}, 20 );