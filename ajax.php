<?php

include( 'functions.php' );

// Define POST variables
$action = $_POST['action'];

// Check if action is update
if ( $action == 'update_client' ) {

    $client_id = $_POST['client_id'];

    // Query client_table
    $client_results = select_query_client( $client_id, 'meta_key, meta_value', $client_table );

    // Check is client_id is found
    if ( $client_results ) {
        // Taking first element and adding back client_id
        $client_output =  array( 'client_id' => $client_id ) + parse_client_meta( $client_results[0] );

        // Parse for meta_key and meta_value
        $client_array = json_encode( array( 'client_info' => json_encode(  $client_output ) ) );

        // Query client_deals_table
        $deal_results = select_query_client( $client_id, 'date_deal_done, date_term', $client_deals_table);
        // Show latest from client_deals_table
        $deal_array = json_encode( array( 'client_deal' => json_encode( $deal_results[0] ) ) );

        // Query schedule_table
        $schedule_results = select_query_client( $client_id, 'date_only, transaction_type, transaction_product_variation, transaction_value, transaction_note', $schedule_table );

        $schedule_array = json_encode( array( 'client_schedule' => json_encode( $schedule_results ) ) );

        // Merge arrays from queries
        $output_array = array_merge( json_decode( $client_array, true ), json_decode( $deal_array, true ), json_decode( $schedule_array, true ) );

        // Convert the objects into JSON string before passing them
        echo json_encode( $output_array );

    } else {
        echo false;
    }
} elseif( $action == 'parse_csv' ) {

    /*
    $file_url = $_POST['url'];

    $filename  = substr( $file_url, strrpos( $file_url, '\\' ) + 1, strrpos( $file_url, '.' ) );
    $table_name  = substr( $filename, 0, strrpos( $filename, '.' ) );


    $file_handle = fopen( $file_url, 'r' );
    /*
    // Parse into meta_key and meta_value for client_table
    $csv_array   = parse_meta_client( read_csv( $file_handle ), $table_name );
    */

    $table_name = $_POST['table_name'];

    $csv_array_pre = $_POST['csv_array'];

    // Get mc_user_id from cookie
    $mc_user_id = $_COOKIE['tally_user_id'];

    // Parse into meta_key and meta_value for client_table
    $csv_array = parse_meta_client( $csv_array_pre, $table_name );

    for ( $i = 0; $i < count( $csv_array ); $i++ ) {
        if ( $i != 0 ) {
            // Insert data into table
            switch ( $table_name ) {
                case $client_table:
                    // Insert data into client table
                    $reference_id = insert_query_client( $csv_array[ $i ][0], $csv_array[ $i ][1], $csv_array[ $i ][2] );

                    break;
                case $client_deals_table:
                    // Insert data into deals table
                    $reference_id = insert_query_client_deals( $csv_array[ $i ][0], $csv_array[ $i ][1], $csv_array[ $i ][2], $csv_array[ $i ][3] );

                    break;
                case $schedule_table:
                    // Insert data into schedule table
                    $reference_id = insert_query_schedule( $csv_array[ $i ][0], $csv_array[ $i ][1], $csv_array[ $i ][2], $csv_array[ $i ][3],  $csv_array[ $i ][4], $csv_array[ $i ][5] );

                    break;
                }

            // Make update to log table
            csv_log_change( $mc_user_id, $table_name, $reference_id, $csv_array[ $i ] );

        }

    }
    echo 'Success';

}

?>
