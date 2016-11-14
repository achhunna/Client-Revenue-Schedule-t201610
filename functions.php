<?php

/* Display errors */
error_reporting( E_ALL );
ini_set( "display_errors", 1 );


// Load WordPress files
require_once( '../../wordpress/wp-load.php' );
require_once( '../../wordpress/wp-includes/wp-db.php' );

// header( 'Content-Type: text/plain' ); // for CSV reading demo

/*
    Define variables
*/

// For Dashboard
$current_month = date( 'm' );



// Return month number to month name
function month_number( $monthNum ) {
    return date("M", mktime(0, 0, 0, $monthNum, 10));
}

// For Client Detail
$client_table       = 'acctg_invoice_clients';
$client_deals_table = 'acctg_invoice_clients_key_dates';
$schedule_table     = 'acctg_invoice_client_schedules';
$change_log_table   = 'acctg_change_log';

$client_id          = 123;

/*
    Table array - All tables have a table structure defined where meta data is included
    about which fields exist, and the prescribed format of the field.
*/

$tables = array(

    'acctg_invoice_clients' => array(
            'name'   =>    'acctg_invoice_clients',
            'fields' => array(
                'acctg_invoice_clients_meta_id' => array(
                    'type'  => 'integer',
                    'wpdb'  => '%d',
                    'write' => false
                ),
                'client_id' => array(
                    'type'  => 'string',
                    'wpdb'  => '%s',
                    'write' => true
                ),
                'meta_key' => array(
                    'type'  => 'string',
                    'wpdb'  => '%s',
                    'write' => true
                ),
                'meta_value' => array(
                    'type'  => 'string',
                    'wpdb'  => '%s',
                    'write' => true
                )
            )

    ),
    'acctg_invoice_clients_key_dates' => array(
        'name'   =>    'acctg_invoice_clients_key_dates',
        'fields' => array(
            'acctg_invoice_clients_key_dates_id' => array(
                'type'  => 'integer',
                'wpdb'  => '%d',
                'write' => false
            ),
            'client_id' => array(
                'type'  => 'string',
                'wpdb'  => '%s',
                'write' => true
            ),
            'date_deal_done' => array(
                'type'  => 'string',
                'wpdb'  => '%s',
                'write' => true
            ),
            'date_effective' => array(
                'type'  => 'string',
                'wpdb'  => '%s',
                'write' => true
            ),
            'date_term' => array(
                'type'  => 'string',
                'wpdb'  => '%s',
                'write' => true
            )
        )
    ),
    'acctg_invoice_client_schedules' => array(
        'name'   =>    'acctg_invoice_client_schedules',
        'fields' => array(
            'acctg_invoice_client_schedules_id' => array(
                'type'  => 'integer',
                'wpdb'  => '%d',
                'write' => false
            ),
            'client_id' => array(
                'type'  => 'string',
                'wpdb'  => '%s',
                'write' => true
            ),
            'date_only' => array(
                'type'  => 'string',
                'wpdb'  => '%s',
                'write' => true
            ),
            'transaction_note' => array(
                'type'  => 'string',
                'wpdb'  => '%s',
                'write' => true
            ),
            'transaction_product_variation' => array(
                'type'  => 'string',
                'wpdb'  => '%s',
                'write' => true
            ),
            'transaction_type' => array(
                'type'  => 'string',
                'wpdb'  => '%s',
                'write' => true
            ),
            'transaction_value' => array(
                'type'  => 'double',
                'wpdb'  => '%f',
                'write' => true
            )
        )
    ),
    'acctg_change_log' => array(
        'name'   =>    'acctg_change_log',
        'fields' => array(
            'log_id' => array(
                'type'  => 'integer',
                'wpdb'  => '%d',
                'write' => false
            ),
            'mc_user_id' => array(
                'type'  => 'string',
                'wpdb'  => '%s',
                'write' => true
            ),
            'source' => array(
                'type'  => 'string',
                'wpdb'  => '%s',
                'write' => true
            ),
            'table_change' => array(
                'type'  => 'string',
                'wpdb'  => '%s',
                'write' => true
            ),
            'reference_id' => array(
                'type'  => 'integer',
                'wpdb'  => '%d',
                'write' => true
            ),
            'log_date' => array(
                'type'  => 'string',
                'wpdb'  => '%s',
                'write' => false
            ),
            'change_type' => array(
                'type'  => 'string',
                'wpdb'  => '%s',
                'write' => true
            ),
            'field_change' => array(
                'type'  => 'string',
                'wpdb'  => '%s',
                'write' => true
            ),
            'old_value' => array(
                'type'  => 'string',
                'wpdb'  => '%s',
                'write' => true
            ),
            'new_value' => array(
                'type'  => 'string',
                'wpdb'  => '%s',
                'write' => true
            )
        )
    )
);



// Declare as global
global $client_table, $client_deals_table, $client_id;


/*
    MySQL query functions
*/
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

// Select query for change log
function select_query_log( $fields, $table ) {
    global $wpdb;

    // Escaping single quote to avoid sql injection
    $fields = addslashes( $fields );

    $results = $wpdb->get_results(
            "
                SELECT     $fields
                FROM       $table
                INNER JOIN $wpdb->users
                           ON $wpdb->users.ID = $table.mc_user_id
            "
    );

    return $results;
}

// wpdb insert function
function insert_new_record( $table, $input_array, $format_array ) {

    global $wpdb;

    $wpdb->insert( $table, $input_array, $format_array );
    // Return count of numbers of rows updated
    return $wpdb->insert_id;
}

// Insert query for client_table
function insert_query_client( $client_id, $meta_key, $meta_value ) {
    global $wpdb, $client_table;
    $table = $client_table;
    /*
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
    */

    $input_array = array(
        'client_id' => $client_id,
        'meta_key' => $meta_key,
        'meta_value' => $meta_value
    );
    $format_array = array(
        '%s',
        '%s',
        '%s'
    );

    return insert_new_record( $table, $input_array, $format_array );
}

// Insert query for client_deals_table
function insert_query_client_deals( $client_id, $date_deal, $date_effective, $date_term ) {
    global $wpdb, $client_deals_table;
    $table = $client_deals_table;

    /*
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
    */

    $input_array = array(
        'client_id' => $client_id,
        'date_deal_done' => $date_deal,
        'date_effective' => $date_effective,
        'date_term' => $date_term
    );
    $format_array = array(
        '%s',
        '%s',
        '%s',
        '%s'
    );

    return insert_new_record( $table, $input_array, $format_array );
}

// Insert query for schedule_table
function insert_query_schedule( $client_id, $date, $note, $product_variation, $type, $value ) {
    global $wpdb, $schedule_table;
    $table = $schedule_table;

    /*
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
    */

    $input_array = array(
        'client_id' => $client_id,
        'date_only' => $date,
        'transaction_note' => $note,
        'transaction_product_variation' => $product_variation,
        'transaction_type' => $type,
        'transaction_value' => $value
    );
    $format_array = array(
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%f'
    );

    return insert_new_record( $table, $input_array, $format_array );

}


// Log changes
function log_change( $mc_user_id, $source, $table_change, $reference_id, $change_type, $field_change, $old_value, $new_value ) {
    // source = csv, app; table_change = acctg_invoice_clients, acctg_invoice_clients_key_dates, acctg_invoice_client_schedules
    global $wpdb, $change_log_table;
    $table = $change_log_table;

    /*
    $reference_id = last_reference_id( $table_change );

    echo 'Reference id: ' . $reference_id . '<br><br>';
    */

    // Using wpdb generated auto_increment value after insert function


    /*
    $wpdb->query( $wpdb->prepare (
            "
                INSERT INTO $table
                ( mc_user_id, source, table_change, reference_id, change_type, field_change, old_value, new_value )
                VALUES ( %s, %s, %s, %s, %s, %s, %f, %f )
            ",
            array(
                $mc_user_id,
                $source,
                $table_change,
                $reference_id,
                $change_type,
                $field_change,
                $old_value,
                $new_value
            )
    ) );
    */

    $input_array = array(
        'mc_user_id' => $mc_user_id,
        'source' => $source,
        'table_change' => $table_change,
        'reference_id' => $reference_id,
        'change_type' => $change_type,
        'field_change' => $field_change,
        'old_value' => $old_value,
        'new_value' => $new_value
    );
    $format_array = array(
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s'
    );

    return insert_new_record( $table, $input_array, $format_array );

}


// Select query for last reference_id
function last_reference_id( $table ) {
    global $wpdb, $client_table, $client_deals_table, $schedule_table;

    // Determine reference_id based on table
    switch ( $table ) {
        case $client_table:
            $reference_id = 'acctg_invoice_clients_meta_id';
            break;
        case $client_deals_table:
            $reference_id = 'acctg_invoice_clients_key_dates_id';
            break;
        case $schedule_table:
            $reference_id = 'acctg_invoice_client_schedules_id';
            break;
    }

    // Query reference_id column from table and get the last stord value
    $result = $wpdb->get_var(
            "
                SELECT $reference_id
                FROM $table
                ORDER BY $reference_id DESC LIMIT 1
            "
    );

    return $result;

}


// CSV log change function
function csv_log_change( $mc_user_id, $table_name, $reference_id, $csv_array ) {
    global $tables;

    $j = 0;

    foreach ( $tables[ $table_name ]['fields'] as $key => $value ) {

        // Check if write is true for field
        if ( $value['write'] ) {


            // Log changes
            log_change( $mc_user_id, 'csv', $table_name, $reference_id, 'add', $key, '', $csv_array[ $j ] );
            $j += 1;
        }

    }

}


/*
    CSV functions
*/
// Read CSV file and return an array of rows
function read_csv( $file ) {
    $rows = array();

    // Read file function, 8192 max characters
    while ( ( $row = fgetcsv( $file, 8192 ) ) !== FALSE ) {
        $rows[] = $row;
    }

    return $rows;
}

// Parse into meta_key and meta_value for client_table
function parse_meta_client( $csv_array, $table_name ) {
    global $client_table;

    // Parse if client_table
    if ( $table_name == $client_table ) {

        $return_array = array();

        foreach ( $csv_array as $key1 => $array ) {
            // Reset meta_value
            $meta_value = '';

            foreach ( $array as $key2 => $value ) {
                // Iterating first row for column heading
                if ( $key2 == 0 ) {
                    if ( $key1 == 0 ) {
                        array_push( $return_array, array( $value, 'meta_key', 'meta_value' ) );
                    } else {
                        // Get client_id
                        $client_id = $value;
                    }
                } else {
                    if ( $key1 == 0 ) {
                        $meta_key .= $value . ',';
                    } else {
                        $meta_value .= $value . ',';
                    }
                }
            }

            // Update return_array if the values are not empty
            if ( !empty( $client_id ) && !empty( $meta_key ) && !empty( $meta_value ) ) {
                // Add second row into array
                array_push( $return_array, array( $client_id, rtrim( $meta_key, ',' ), rtrim( $meta_value, ',') ) );
            }

        }

        return $return_array;

    } else {
        return $csv_array;
    }
}

/*
    Other functions
*/
// Parse client meta_key and meta_value into array( key, value )
function parse_client_meta( $output ) {
    foreach ( $output as $key => $value ) {

        $array      = explode( ',', $value );
        $fill_array = array();

        foreach ( $array as $item ) {
            // Push item into fill_array and remove empty space trim()
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
