<?php
// 2025-05-03-import-gac-hardlopers.php

if ( ! defined('WP_CLI') && php_sapi_name()!=='cli' ) {
    return;
}
global $wpdb;

// Skip if already imported
if ( get_option('hardloop_manager_imported_gac', false) ) {
    WP_CLI::warning('Import already run, skipping.');
    return;
}

// Your old table name
$old_table = 'GAC_hardlopers';

// Fetch all rows
$rows = $wpdb->get_results( "SELECT * FROM `{$old_table}`" );

if ( empty( $rows ) ) {
    WP_CLI::success( 'No rows found in ' . $old_table );
    update_option('hardloop_manager_imported_gac', true);
    return;
}

foreach ( $rows as $r ) {
    // Build post title
    $title = trim("{$r->voornaam} {$r->achternaam}");
    $pid   = wp_insert_post([
        'post_type'   => 'loper',
        'post_title'  => $title,
        'post_status' => 'publish',
    ]);
    if ( is_wp_error($pid) ) {
        WP_CLI::warning("Could not insert loper for old ID {$r->id}");
        continue;
    }

    // Map old columns → ACF field names
    $map = [
        'voornaam'            => 'voornaam',
        'achternaam'          => 'achternaam',
        'email'               => 'email',
        'geboortedatum'       => 'geboortedatum',
        'noodnummer'          => 'noodnummer',
        'loopervaring'        => 'loopervaring',
        'gewenst_pace'        => 'gewenst_pace',
        'doel_2025'           => 'doel_2025',
        'shirt'               => 'shirt',
        'blessures'           => 'blessures',
        'opmerkingen'         => 'opmerkingen',
        'trainer_opmerkingen' => 'trainer_opmerkingen',
        'status'              => 'status',
        'created_at'          => 'created_at',
        'updated_at'          => 'updated_at',
        'password'            => 'password',
        'trainer_email'       => 'trainer_email',
        'actief'              => 'actief',
        'Gestart'             => 'gestart',
        'Gestopt'             => 'gestopt',
    ];

    foreach ( $map as $col => $field ) {
        if ( isset( $r->$col ) ) {
            $val = $r->$col;
            // Update ACF field
            update_field( $field, $val, $pid );
            // Fallback to post_meta
            update_post_meta( $pid, $field, $val );
        }
    }

    WP_CLI::log("Imported “{$title}” (old ID {$r->id}) as post {$pid}");
}

// Mark as done
update_option('hardloop_manager_imported_gac', true);
WP_CLI::success('Imported ' . count($rows) . ' hardlopers.');
