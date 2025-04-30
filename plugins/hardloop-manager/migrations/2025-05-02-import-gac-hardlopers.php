<?php
// 2025-05-02-import-gac-hardlopers.php
// Deze migration importeert rijen uit de oude gac_hardlopers-tabel
// en zet ze om in Loper-CPT posts + ACF-veldmeta.

if ( ! defined( 'WP_CLI' ) && php_sapi_name() !== 'cli' ) {
    return;
}

global $wpdb;

// voorkom dubbele import
if ( get_option( 'hardloop_manager_imported_gac', false ) ) {
    WP_CLI::warning( 'GAC hardlopers already imported, skipping.' );
    return;
}

$old_table = $wpdb->prefix . 'gac_hardlopers';
$rows = $wpdb->get_results( "SELECT * FROM {$old_table}" );

if ( empty( $rows ) ) {
    WP_CLI::success( 'No rows found in ' . $old_table );
    update_option( 'hardloop_manager_imported_gac', true );
    return;
}

foreach ( $rows as $r ) {
    // maak een nieuwe Loper post
    $post_id = wp_insert_post([
        'post_type'   => 'loper',
        'post_title'  => trim($r->voornaam . ' ' . $r->achternaam),
        'post_status' => 'publish',
    ]);

    if ( is_wp_error( $post_id ) ) {
        WP_CLI::warning( 'Could not insert loper for old ID ' . $r->id );
        continue;
    }

    // mappen van kolommen naar ACF-veld-namen
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
        // date fields
        'Gestart'             => 'gestart',
        'Gestopt'             => 'gestopt',
    ];

    foreach ( $meta_map as $col => $field_name ) {
        if ( isset( $r->$col ) ) {
            update_field( $field_name, $r->$col, $post_id );
        }
    }

    WP_CLI::log( "Imported loper {$r->voornaam} {$r->achternaam} (old ID {$r->id}) as post {$post_id}" );
}

// markeer als gedaan
update_option( 'hardloop_manager_imported_gac', true );
WP_CLI::success( 'Imported ' . count( $rows ) . ' hardlopers from GAC.' );
