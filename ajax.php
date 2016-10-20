<?php

include( 'functions.php' );

// Define POST variables
$action    = $_POST['action'];
$client_id = $_POST['client_id'];

// Check if action is update
if ( $action == 'update' ) {

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

        $schedule_array       = json_encode( array( 'client_schedule' => json_encode( $schedule_results ) ) );

        // Merge arrays from queries
        $output_array = array_merge( json_decode( $client_array, true ), json_decode( $deal_array, true ), json_decode( $schedule_array, true ) );

        // Convert the objects into JSON string before passing them
        echo json_encode( $output_array );

    } else {
        echo 'Client_id not found';
    }
}

?>
