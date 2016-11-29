<?php
/**
 *
 *    Functions
 *
 */

// Display errors
error_reporting( E_ALL );
ini_set( "display_errors", 1 );

// Load WordPress files
require_once( '../wp/wp-load.php' );
require_once( '../wp/wp-includes/wp-db.php' );

/*
    Define variables
*/

// Set default time zone
date_default_timezone_set( 'America/Los_Angeles' );

// Return month number to month name
function month_number( $monthNum ) {
    return date("M", mktime(0, 0, 0, $monthNum, 10));
}

// Defining table names
$client_table       = 'acctg_invoice_clients';
$client_deals_table = 'acctg_invoice_clients_key_dates';
$schedule_table     = 'acctg_invoice_client_schedules';
$change_log_table   = 'acctg_change_log';
// Default client to load
$client_id          = 123;

// Declare as global
global $client_table, $client_deals_table, $client_id;


/*
    Table array - All tables have a table structure defined where meta data is included
    about which fields exist, and the prescribed format of the field.
*/

$tables = array(
    'acctg_invoice_clients' => array(
            'name'   =>    'acctg_invoice_clients',
            'fields' => array(
                'acctg_invoice_clients_meta_id' => array(
                    'type'  => 'numeric',
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
                'type'  => 'numeric',
                'wpdb'  => '%d',
                'write' => false
            ),
            'client_id' => array(
                'type'  => 'string',
                'wpdb'  => '%s',
                'write' => true
            ),
            'date_deal_done' => array(
                'type'    => 'date',
                'wpdb'    => '%s',
                'format'  => 'Y-m-d',
                'write'   => true
            ),
            'date_effective' => array(
                'type'    => 'date',
                'wpdb'    => '%s',
                'format'  => 'Y-m-d',
                'write'   => true
            ),
            'date_term' => array(
                'type'    => 'date',
                'wpdb'    => '%s',
                'format'  => 'Y-m-d',
                'write'   => true
            )
        )
    ),
    'acctg_invoice_client_schedules' => array(
        'name'   =>    'acctg_invoice_client_schedules',
        'fields' => array(
            'acctg_invoice_client_schedules_id' => array(
                'type'  => 'numeric',
                'wpdb'  => '%d',
                'write' => false
            ),
            'client_id' => array(
                'type'  => 'string',
                'wpdb'  => '%s',
                'write' => true
            ),
            'date_only' => array(
                'type'    => 'date',
                'wpdb'    => '%s',
                'format'  => 'Y-m-d',
                'write'   => true
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
                'type'  => 'numeric',
                'wpdb'  => '%f',
                'write' => true
            )
        )
    ),
    'acctg_change_log' => array(
        'name'   =>    'acctg_change_log',
        'fields' => array(
            'log_id' => array(
                'type'  => 'numeric',
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
                'type'  => 'numeric',
                'wpdb'  => '%d',
                'write' => true
            ),
            'log_date' => array(
                'type'   => 'date',
                'wpdb'   => '%s',
                'format' => 'Y-m-d H:i:s',
                'write'  => false
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

/*
    Helper functions
*/

// Function to validate user input
function validate_user_input( $table_name, $user_input ) {

	foreach ( $user_input as $field => $value ) {

		if ( field_exists( $table_name, $field ) ) {

			if ( is_correct_type( $table_name, $field, $value) ) {
				//echo "Correct: {$field} - {$value}\n";
			} else {
				//echo "ERROR: VALUE NOT CORRECT FOR TYPE: {$field} - {$value}\n";
                return false;
			}
		} else {
			//echo "ERROR: FIELD NOT EXIST {$field}\n";
            return false;
		}
	}
    // if all entries are correct, return true
    return true;
}

// Check input type for validation
function is_correct_type( $table_name, $field_name, $field_value) {

	global $tables;

	// string
	if ( $tables[ $table_name ]['fields'][ $field_name ]['type'] == 'string' ) {

		if ( is_string( $field_value ) ) {
			return true;
		} else {
			return false;
		}

	}

	// date
	 if ( $tables[ $table_name ]['fields'][ $field_name ]['type'] == 'date' ) {

		//echo $tables[$table_name]['fields'][$field_name]['format'];

            if ( is_valid_date( $field_value, $tables[ $table_name ]['fields'][ $field_name ]['format'] ) ) {
                return true;
            } else {
                return false;
            }

        }

	// numeric
	if ( $tables[ $table_name ]['fields'][ $field_name ]['type'] == 'numeric' ) {

            if ( is_numeric( $field_value ) ) {
                return true;
            } else {
                return false;
            }

        }
}

// Check date format
function is_valid_date( $date, $format ) {

    $d = DateTime::createFromFormat( $format, $date );
    return $d && $d -> format( $format ) == $date;

}

// Check if field exists in table
function field_exists( $table_name, $field_name ) {

	global $tables;

	if ( is_array( $tables[ $table_name ]['fields'][ $field_name ] ) ) {
		return true;
	} else {
		return false;
	}

}

// Check if table exists
function tn( $table_name ) {

	global $tables;

	if ( isset( $tables[ $table_name ]['name']  ) ) {
		return $tables[ $table_name ]['name'];
    }

}

// Validate table and fields
function validate_table_fields( $table, $fields ) {
    // Check if table exists or fields is '*'
    if ( !tn( $table ) ) {
        return false;
    } else if ( $fields == '*' ) {
        return true;
    }

    // Check each field in table to prevent SQL injection
    $fields_check = false;
    $fields_array = explode( ', ', $fields );

    foreach ( $fields_array as $fields_value ) {

        if ( field_exists( $table, $fields_value ) ) {
            $fields_check = true;
        } else {
            $fields_check = false;
            break;
        }
    }
    return $fields_check;
}

/*
    MySQL query functions
*/
// Select query using client_id
function select_query_client( $client_id, $fields, $table ) {

    global $wpdb;

    $results = false;

    if ( validate_table_fields( $table, $fields ) ) {

        $results = $wpdb->get_results( $wpdb->prepare (
                "
                    SELECT $fields
                    FROM   $table
                    WHERE  client_id = %s
                ",
                $client_id
        ) );

    }
    return $results;
}

// Select query to retrieve Client name
function select_query_client_name( $client_id, $table ) {

    global $wpdb;

    $results = select_query_client( $client_id, 'meta_key, meta_value', tn( $table ) );
    // Parse meta_key and meta_value into original array to extract name
    return parse_client_meta( $results[0] )['name'];
}

// Select query using mc_user_id
function select_query_user( $mc_user_id ) {

    global $wpdb;

    $results = $wpdb->get_var( $wpdb->prepare (
            "
                SELECT display_name
                FROM   $wpdb->users
                WHERE  ID = %s
            ",
            $mc_user_id
    ) );
    return $results;
}

// Select query for change log
function select_query_log( $fields, $table ) {

    global $wpdb;

    $results = false;

    if ( validate_table_fields( $table, $fields ) ) {
        // Update mc_user_id with display_name
        $fields = substr( $fields, 0, strrpos( $fields, ',' ) ) . ', display_name';

        $results = $wpdb->get_results(
                "
                    SELECT     $fields
                    FROM       $table
                    INNER JOIN $wpdb->users
                               ON $wpdb->users.ID = $table.mc_user_id
                "
        );

    }
    return $results;
}

// Select query for change log with sort
function select_query_log_sort( $fields, $table, $sort_field, $sort_order ) {

    global $wpdb, $tables;

    $results = false;

    // $sort_order is for asc or desc

    if ( validate_table_fields( $table, $fields ) && validate_table_fields( $table, $sort_field ) ) {
        // Replace mc_user_id with display_name
        $fields = str_replace( 'mc_user_id', 'display_name', $fields );

        $results = $wpdb->get_results(
                "
                    SELECT     $fields
                    FROM       $table
                    INNER JOIN $wpdb->users
                               ON $wpdb->users.ID = $table.mc_user_id

                    ORDER BY   $sort_field $sort_order
                "
        );
    }
    return $results;
}

// Select query for change log by log_id
function select_query_log_id( $fields, $table, $log_id ) {

    global $wpdb;

    $results = false;

    if ( validate_table_fields( $table, $fields ) ) {
        $results = $wpdb->get_results( $wpdb->prepare (
                "
                    SELECT $fields
                    FROM   $table
                    WHERE  log_id = %d
                ",
                $log_id
        ) );
    }
    return $results;
}

// Select query for schedule id
function select_query_table_id( $schedule_id, $fields, $table ) {

    global $wpdb, $tables;

    $results = false;

    // Get the first key from array for id
    $id = key( $tables[ $table ]['fields'] );

    if ( validate_table_fields( $table, $fields ) ) {

        $results = $wpdb->get_results( $wpdb->prepare (
                "
                    SELECT $fields
                    FROM   $table
                    WHERE  $id = %d
                ",
                $schedule_id
        ) );

    }
    return $results;
}

// Select query for fields without criteria
function select_query_no_criteria( $fields, $table ) {

    global $wpdb;
    $results = false;

    if ( validate_table_fields( $table, $fields ) ) {

        $results = $wpdb->get_results( $wpdb->prepare (
                "
                    SELECT $fields
                    FROM   $table
                    WHERE  %s
                ",
                '1'
        ) );

    }
    return $results;
}

// Get client id from client name
function get_client_id( $client_name, $table ) {

    $client_id_results = select_query_no_criteria( 'client_id', $table );
    $meta_results = select_query_no_criteria( 'meta_key, meta_value', $table );

    $id_array = array();
    $name_array = array();

    foreach ( $client_id_results as $item ) {
        array_push( $id_array, $item->client_id );
    }

    foreach ( $meta_results as $item ) {
        array_push( $name_array, parse_client_meta( $item )['name'] );
    }

    $return_array = array_combine( $name_array, $id_array );
    return $return_array[ $client_name ];
}

// wpdb insert function
function insert_new_record( $table, $input_array, $format_array ) {

    global $wpdb;

    // Validate user input
    if ( validate_user_input( $table, $input_array ) ) {

        $wpdb->insert( tn( $table ), $input_array, $format_array );
        // Return count of numbers of rows updated
        return array( 'id' => $wpdb->insert_id );
    }
}

// Determine field changes to update
function track_field_change( $where, $table, $input_array ) {

    if ( is_array( $where ) ) {
        if ( key( $where ) == 'client_id' ) {
            // If client id
            $old_values = select_query_client( current( $where ), '*', $table );
        } else {
            // Else schedule id
            $old_values = select_query_table_id( current( $where ), '*', $table );
        }
    }

    if ( $old_values ) {
        // Define start of array
        $position = 0;
        $old_array = array();
        $new_array = array();
        $new_array_field = array();

        foreach ( $old_values[0] as $key => $value ) {

            if ( !empty( $input_array[ $key ] ) && $input_array[ $key ] != $value ) {
                // Define position in array to update old value
                $old_array[ $position - 1 ] = $value;
                $new_array[ $position - 1 ] = $input_array[ $key ];
                // Define key in array to update new value
                $new_array_field[ $key ] = $input_array[ $key ];
                //return $input_array;
            }
            $position++;
        }

        // If any of the arrays are empty
        if ( empty( $old_array ) || empty( $new_array ) || empty( $new_array_field ) ) {
            echo "Empty \n";
            return false;
        } else {
            $new_input_array = array_merge( array( 'old' => $old_array ), array( 'new' => $new_array ), array( 'new_field' => $new_array_field ) );

            echo 'track field change ';
            print_r( $new_input_array );
            // Returns $new_input_array as empty
            return $new_input_array;
        }
    }
    return false;
}

// wpdb update function
function update_record( $table, $input_array, $where, $format_array, $format_where ) {

    global $wpdb;

    // Validate user input and where field used to update data
    if ( validate_user_input( $table, $input_array ) && validate_user_input( $table, $where ) ) {

        // Update input array with fields changed
        $new_input_array = track_field_change( $where, $table, $input_array );

        if ( !empty( $new_input_array ) ) {
            $wpdb->update( tn( $table ), $new_input_array['new_field'], $where, $format_array, $format_where );
            if ( empty( $wpdb->insert_id ) ) {
                $return_id = current( $where );
            } else {
                $return_id = $wpdb->insert_id;
            }
            echo 'Update record to send: ' . $return_id . ', ';
            print_r( $new_input_array['old'] );
            echo ', ';
            print_r( $new_input_array['new'] );
            // Return count of numbers of rows updated
            return array( 'id' => $return_id, 'old' => $new_input_array['old'], 'new' => $new_input_array['new'] );
        }
    }
}

// Function to determine update or insert record
function determine_insert_update( $client_id, $input_array, $format_array, $table ) {
    // Check if client_id exists
    if ( select_query_client( $client_id, 'client_id', $table ) ) {

        $where = array(
            'client_id' => $client_id
        );
        $format_where = array(
            '%s'
        );
        // Update client_table
        return update_record( $table, $input_array, $where, $format_array, $format_where );

    } else {
        return insert_new_record( $table, $input_array, $format_array );
    }
}

// Insert query for client_table
function insert_query_client( $client_id, $meta_key, $meta_value ) {

    global $wpdb, $client_table;
    $table = $client_table;

    $input_array = array(
        'client_id'  => $client_id,
        'meta_key'   => $meta_key,
        'meta_value' => $meta_value
    );
    $format_array = array(
        '%s',
        '%s',
        '%s'
    );
    // Insert or update, based on client id
    return determine_insert_update( $client_id, $input_array, $format_array, $table );
}

// Insert query for client_deals_table
function insert_query_client_deals( $client_id, $date_deal, $date_effective, $date_term ) {

    global $wpdb, $client_deals_table;
    $table = $client_deals_table;

    $input_array = array(
        'client_id'      => $client_id,
        'date_deal_done' => $date_deal,
        'date_effective' => $date_effective,
        'date_term'      => $date_term
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

    $input_array = array(
        'client_id'                     => $client_id,
        'date_only'                     => $date,
        'transaction_note'              => $note,
        'transaction_product_variation' => $product_variation,
        'transaction_type'              => $type,
        'transaction_value'             => $value
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

    // Check for empty old value
    if ( empty ( $old_value ) ) {
        $old_value = '';
    }

    $input_array = array(
        'mc_user_id'   => $mc_user_id,
        'source'       => $source,
        'table_change' => $table_change,
        'reference_id' => $reference_id,
        'change_type'  => $change_type,
        'field_change' => $field_change,
        'old_value'    => $old_value,
        'new_value'    => $new_value
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

// Update log change function
function update_log_change( $mc_user_id, $source, $table_name, $reference_id, $old_array, $new_array ) {

    global $tables;

    $j = 0;

    if ( empty( $old_array ) ) {
        $update_type = 'add';
    } else {
        $update_type = 'update';
    }

    if ( !empty( $reference_id ) ) {
        foreach ( $tables[ $table_name ]['fields'] as $key => $value ) {
            // Change field write property if updating with old array
            if ( $update_type == 'update' && empty( $old_array[ $j ] ) ) {
                $value['write'] = false;
                $j++;
            }

            // Check if write is true for field
            if ( $value['write'] ) {
                // Log changes
                log_change( $mc_user_id, $source, $table_name, $reference_id, $update_type, $key, $old_array[ $j ], $new_array[ $j ] );
                $j++;
            }

        }
    }
}

// Delete records by client id
function delete_query_client_id( $client_id, $table ) {

    global $wpdb;

    // Query items to be deleted
    $deleted = select_query_client( $client_id, '*', $table );

    $wpdb->delete( tn( $table ), array( 'client_id' => $client_id ) );

    return $deleted;
}

// Clear database, for test purpose only
function delete_all() {
    global $wpdb, $tables;

    foreach ( $tables as $key => $value ) {
        // Delete all rows
        $wpdb->query(
                $wpdb->prepare(
                    "
                        DELETE from $key
                        WHERE 1
                    "
                    )
            );
        // Reset auto Increment
        $wpdb->query(
                $wpdb->prepare(
                    "
                        ALTER TABLE $key
                        AUTO_INCREMENT = 1
                    "
                    )
        );
    }
}

/*
    Other functions
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

?>
