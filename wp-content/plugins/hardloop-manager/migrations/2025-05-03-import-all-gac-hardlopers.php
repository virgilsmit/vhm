<?php
/**
 * Migration: Import all runners from GAC_hardlopers table
 * Date: 2025-05-03
 */

global $wpdb;

// Bepaal de juiste tabelnaam (met WP-prefix)
$table = $wpdb->prefix . 'GAC_hardlopers';

// Zorg dat de tabel bestaat
if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table}'" ) !== $table ) {
    error_log( "Table {$table} does not exist." );
    return;
}

// Haal alle rijen op
$rows = $wpdb->get_results( "SELECT * FROM `{$table}`", ARRAY_A );
if ( empty( $rows ) ) {
    error_log( "No runners found in {$table}." );
    return;
}

foreach ( $rows as $loper ) {
    // Stel de data samen — pas de kolomnamen aan als jouw tabel anders heet
    $post_data = [
        'post_title'   => wp_strip_all_tags( $loper['naam'] ?? 'Onbekende loper' ),
        'post_content' => '', // vul in als je meer velden wilt mappen
        'post_date'    => current_time( 'mysql' ),
        'post_status'  => 'publish',
        'post_type'    => 'loper',
    ];

    // Maak of update: voorkom dubbele imports
    // Je kunt hier eventueel op email of naam checken:
    $existing = get_posts( [
        'post_type'  => 'loper',
        'meta_key'   => 'email',
        'meta_value' => $loper['email'] ?? '',
        'fields'     => 'ids',
    ] );

    if ( ! empty( $existing ) ) {
        // Update in plaats van insert, of sla over:
        continue;
    }

    $post_id = wp_insert_post( $post_data, true );
    if ( is_wp_error( $post_id ) ) {
        error_log( "Failed to insert loper {$post_data['post_title']}: " . $post_id->get_error_message() );
        continue;
    }

    // Optioneel: zet ACF-velden via update_field()
    if ( function_exists( 'update_field' ) ) {
        if ( isset( $loper['voornaam'] ) ) {
            update_field( 'voornaam', $loper['voornaam'], $post_id );
        }
        if ( isset( $loper['achternaam'] ) ) {
            update_field( 'achternaam', $loper['achternaam'], $post_id );
        }
        if ( isset( $loper['email'] ) ) {
            update_field( 'email', $loper['email'], $post_id );
        }
        // … enzovoorts voor al je kolommen …
    }
}