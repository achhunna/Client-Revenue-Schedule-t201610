<?php

include( 'tally-functions.php' );

// Define POST variables
$action = $_POST[ 'action' ];
$client_id = $_POST[ 'client_id' ];
$fields = $_POST[ 'fields' ];
$table = $_POST[ 'table' ];

// Check if action is update
if ( $action == 'update' ) {

    // Query client_table
    $client_results = select_query_client( $client_id, $fields, $table );

    if ( $client_results ) {
        $client_output = $client_results[0];
        $array1 = json_encode( parse_client_meta( $client_output ) );

        // Query client_deals_table
        $deal_results = select_query_client( $client_id, 'date_deal_done, date_term', 'acctg_invoice_clients_key_dates');
        $array2 = json_encode( $deal_results[0] );

        $output_array = array_merge( json_decode( $array1, true ), json_decode( $array2, true ) );

        echo json_encode( $output_array );

    } else {
        echo 'false';
    }
}

?>
