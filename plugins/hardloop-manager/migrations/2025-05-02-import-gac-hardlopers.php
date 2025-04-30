<?php
// 2025-05-02-import-gac-hardlopers.php
// Deze migration importeert rijen uit de oude GAC_hardlopers-tabel
// en zet ze om in Loper-CPT posts + ACF-veldmeta.

if ( ! defined( 'WP_CLI' ) && php_sapi_name() !== 'cli' ) {
    // alleen draaien via WP-CLI of CI
    return;
}

global $wpdb;

// voorkom dubbele import
if ( get_option( 'hardloop_manager_imported_gac', false ) ) {
    WP_CLI::warning( 'GAC hardlopers already imported, skipping.' );
    return;
}

// gebruik exact de oude tabelnaam
$old_table = 'GAC_hardlopers';

$rows = $wpdb->get_results( "SELECT * FROM `{$old_table}`" );

if ( empty( $rows ) ) {
    WP_CLI::success( 'No rows found in ' . $old_table );
    update_option( 'hardloop_manager_imported_gac', true );
    return;
}

foreach ( $rows as $r ) {

    // post title op basis van voornaam + achternaam
    $title = trim( (string) $r->voornaam . ' ' . (string) $r->achternaam );

    $post_id = wp_insert_post( [
        'post_type'   => 'loper',
        'post_title'  => $title,
        'post_status' => 'publish',
    ] );

    if ( is_wp_error( $post_id ) ) {
        WP_CLI::warning( 'Could not insert loper for old ID ' . $r->id );
        continue;
    }

    // mappen van old-kolom naar ACF-veld-name
    $meta_map = [
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
        'Niveau'              => 'niveau',
        'actief'              => 'actief',
        // datum-velden, let op formaat in oude tabel (YYYY-MM-DD of anders)
        'Gestart'             => 'gestart',
        'Gestopt'             => 'gestopt',
    ];

    foreach ( $meta_map as $col => $field_name ) {
        if ( isset( $r->$col ) ) {
            update_field( $field_name, $r->$col, $post_id );
        }
    }

    WP_CLI::log( "Imported loper “{$title}” (old ID {$r->id}) as post {$post_id}" );
}

// markeer als gedaan, zodat bij een retry niet nogmaals wordt geïmporteerd
update_option( 'hardloop_manager_imported_gac', true );
WP_CLI::success( 'Imported ' . count( $rows ) . ' hardlopers from GAC.' );
