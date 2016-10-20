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
        $client_output = $client_results[0];

        // Parse for meta_key and meta_value
        $client_array = json_encode( parse_client_meta( $client_output ) );

        // Query client_deals_table
        $deal_results = select_query_client( $client_id, 'date_deal_done, date_term', $client_deals_table);
        // Show latest from client_deals_table
        $deal_array = json_encode( $deal_results[0] );

        // Query schedule_table
        $schedule_results = select_query_client( $client_id, 'date_only, transaction_type, transaction_product_variation, transaction_value, transaction_note', $schedule_table );

        $schedule_array       = array( 'schedule_array' => json_encode( $schedule_results ) );
        $schedule_group_array = json_encode( $schedule_array );

        // Merge arrays from queries
        $output_array = array_merge( json_decode( $client_array, true ), json_decode( $deal_array, true ), json_decode( $schedule_group_array, true ) );

        // Convert the objects into JSON string before passing them
        echo json_encode( $output_array );

    } else {
        echo 'Client_id not found';
    }
}

?>
