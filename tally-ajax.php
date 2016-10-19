<?php

include( 'tally-functions.php' );

// Define POST variables
$action = $_POST['action'];
$client_id = $_POST['client_id'];
$fields = $_POST['fields'];
$table = $_POST['table'];

// Check if action is update
if ( $action == 'update' ) {

    // Query client_table
    $client_results = select_query_client( $client_id, $fields, $table );

    // Check is client_id is found
    if ( $client_results ) {
        $client_output = $client_results[0];

        // Parse for meta_key and meta_value
        $array1 = json_encode( parse_client_meta( $client_output ) );

        // Query client_deals_table
        $deal_results = select_query_client( $client_id, 'date_deal_done, date_term', $client_deals_table);
        // Show latest from client_deals_table
        $array2 = json_encode( $deal_results[0] );

        // Query schedule_table
        $schedule_results = select_query_client( $client_id, 'date_only, transaction_type, transaction_product_variation, transaction_value, transaction_note', $schedule_table );
        $array3 = json_encode( $schedule_results );

        // Merge arrays from queries
        $output_array = array_merge( json_decode( $array1, true ), json_decode( $array2, true ), json_decode( $array3, true ) );

        echo json_encode( $output_array );

    } else {
        echo 'Client_id not found';
    }
}

?>
