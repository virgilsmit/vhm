<?php
/**
 * 2025-05-03-import-all-gac-hardlopers.php
 */

global $wpdb;
$table = 'GAC_hardlopers';  // gebruik exact deze naam

// Controleer of de tabel bestaat
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
    $post_data = [
        'post_title'   => wp_strip_all_tags( $loper['naam'] ?? 'Onbekende loper' ),
        'post_content' => '',
        'post_date'    => current_time( 'mysql' ),
        'post_status'  => 'publish',
        'post_type'    => 'loper',
    ];

    $post_id = wp_insert_post( $post_data, true );
    if ( is_wp_error( $post_id ) ) {
        error_log( "Failed to insert loper {$post_data['post_title']}: " . $post_id->get_error_message() );
    }
}