<?php

/* Display errors */
error_reporting( E_ALL );
ini_set( "display_errors", 1 );


// Load WordPress files
require_once( '../../wordpress/wp-load.php' );
require_once( '../../wordpress/wp-includes/wp-db.php' );

// header( 'Content-Type: text/plain' ); // for CSV reading demo

// Define variables
$client_table       = 'acctg_invoice_clients';
$client_deals_table = 'acctg_invoice_clients_key_dates';
$schedule_table     = 'acctg_invoice_client_schedules';
$change_log_table   = 'acctg_change_log';
$client_id          = 123;

global $client_table, $client_deals_table, $client_id;


/* MySQL query functions */
// Select query using client_id
function select_query_client( $client_id, $fields, $table ) {
    global $wpdb;

    // Escaping single quote to avoid sql injection
    $fields = addslashes( $fields );

    $results = $wpdb->get_results( $wpdb->prepare (
            "
                SELECT $fields
                FROM $table
                WHERE client_id = %s
            ",
            $client_id
    ) );

    return $results;
}

// Select query using mc_user_id
function select_query_user( $mc_user_id ) {
    global $wpdb;

    $results = $wpdb->get_var( $wpdb->prepare (
            "
                SELECT display_name
                FROM $wpdb->users
                WHERE ID = %s
            ",
            $mc_user_id
    ) );

    return $results;
}

// Insert query for client_table
function insert_query_client( $client_id, $meta_key, $meta_value ) {
    global $wpdb, $client_table;
    $table = $client_table;

    $wpdb->query( $wpdb->prepare (
            "
                INSERT INTO $table
                ( client_id, meta_key, meta_value )
                VALUES ( %s, %s, %s )
            ",
            array(
                $client_id,
                $meta_key,
                $meta_value
            )
    ) );
}

// Insert query for client_deals_table
function insert_query_client_deals( $client_id, $date_deal, $date_effective, $date_term ) {
    global $wpdb, $client_deals_table;
    $table = $client_deals_table;

    $wpdb->query( $wpdb->prepare (
            "
                INSERT INTO $table
                ( client_id, date_deal_done, date_effective, date_term )
                VALUES ( %s, %s, %s, %s )
            ",
            array(
                $client_id,
                $date_deal,
                $date_effective,
                $date_term
            )
    ) );
}

// Insert query for schedule_table
function insert_query_schedule( $client_id, $date, $note, $product_variation, $type, $value ) {
    global $wpdb, $schedule_table;
    $table = $schedule_table;

    $wpdb->query( $wpdb->prepare (
            "
                INSERT INTO $table
                ( client_id, date_only, transaction_note, transaction_product_variation, transaction_type, transaction_value )
                VALUES ( %s, %s, %s, %s, %s, %f )
            ",
            array(
                $client_id,
                $date,
                $note,
                $product_variation,
                $type,
                $value
            )
    ) );

}


// Log changes
function log_change( $mc_user_id, $source, $acctg_change_type, $reference_id, $change_type, $field_change, $old_value, $new_value ) {
    // source = csv, app; acctg_change_type = client, schedule
    global $wpdb, $change_log_table;
    $table = $change_log_table;

    $wpdb->query( $wpdb->prepare (
            "
                INSERT INTO $table
                ( mc_user_id, source, acctg_change_type, reference_id, change_type, field_change, old_value, new_value )
                VALUES ( %s, %s, %s, %s, %s, %s, %f, %f )
            ",
            array(
                $mc_user_id,
                $source,
                $acctg_change_type,
                $reference_id,
                $change_type,
                $field_change,
                $old_value,
                $new_value
            )
    ) );

}

/* CSV functions */
// Read CSV file and return an array of rows
function read_csv( $file ) {
    $rows = array();

    // Read file function, 8192 max characters
    while ( ( $row = fgetcsv( $file, 8192 ) ) !== FALSE ) {
        $rows[] = $row;
    }

    return $rows;
}


/* Other functions */
// Parse client meta_key and meta_value into array( key, value )
function parse_client_meta( $output ) {
    foreach ( $output as $key => $value ) {

        $array      = explode( ',', $value );
        $fill_array = array();

        foreach ( $array as $item ) {
            // Push item into fill_array and remove empty space
            array_push( $fill_array, trim ( $item ) );
        }

        if ( $key == 'meta_key' ){
            $k = $fill_array;
        } else {
            $v = $fill_array;
        }

    }
    return array_combine ( $k, $v );
}

// WP login check function
function wp_tally_login( $username, $password ) {
    $credentials                  = array();
    $credentials['user_login']    = $username;
    $credentials['user_password'] = $password;
    $credentials['remember']      = false;

    $user = wp_signon( $credentials, false );

    if ( $user ) {
        wp_set_current_user( $user->ID ); // Set as current user
        return $user;
    }
}

// Set user cookie
function set_cookie( $user ) {
    setcookie( 'tally_user_id', $user->ID, time() + (86400 * 30), "/"); // 86400 = 1 day
}


/*
Early work
// get_results

$sql = $wpdb->escape( "SELECT * FROM wp_options WHERE option_id = 1" );
$results = $wpdb->get_results( $sql, OBJECT );

foreach ( $results as $key => $value ) {
    // Convert to JSON to print
    echo json_encode( $value );
}

echo '<br>';
echo "\n\t";

// get_var

$user = 'tally-admin';
$results = $wpdb->get_var( $wpdb->prepare (
        "
            SELECT COUNT(*)
            FROM $wpdb->users
            WHERE user_login = %s
        ", $user
        ) );

if ( $results ) {
    echo 'tally-admin is a user <br>';
}


echo "\n\t";
*/

?>
