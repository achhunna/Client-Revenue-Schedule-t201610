<?php
include( 'functions.php' );

// Get the autocomplete term variable
$term = $_GET[ "term" ];

$name_array = array();
$meta_results = select_query_no_criteria( 'meta_key, meta_value', $client_table );

foreach ( $meta_results as $item ) {
    $client_name = parse_client_meta( $item )['name'];
    if ( strpos( strtoupper( $client_name ), strtoupper( $term ) ) !== false ) {
        array_push( $name_array, $client_name );
    }
}

echo json_encode( $name_array );
?>
