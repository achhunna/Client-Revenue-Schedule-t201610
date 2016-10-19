// Switch partial based on selection
function switch_partial( selection ) {
    // Hide all partial divs
    $( '.partial' ).hide();
    // Show selection div based on id
    $( '#' + selection ).show();
}

// Update client_id query
function update() {
    client_id = $('#client-id').html();

    // Ajax call to update client
    $.ajax({
        type: 'post',
        url: 'tally-ajax.php',
        data: '&action=update&client_id=' + client_id + '&fields=meta_key, meta_value&table=acctg_invoice_clients',
        success: function( data ) {

            if( !data ) {
                console.log( "Error" );
            } else {
                update_dom( data );
            }
        }
    });
}

// Update DOM elements
function update_dom( data ) {
    var parse_data = $.parseJSON( data );
    // Iterate through parse_data object to update text in field Div id
    for ( field in parse_data ) {
        console.log( field + ': ' + parse_data[ field ] );
        $( '#' + field ).text( parse_data[ field ]);
    }

}

// Delete cookie
function delete_cookie( cookie_name ) {
    document.cookie = cookie_name + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
    location.reload();
}


// Document ready
$( document ).ready( function() {

    // Load default div
    switch_partial( 'client_detail' );


    // Client search box
    $( '.client_box' ).click( function() {
        $( this ).html( '' );
    } )
    $( '.client_box' ).focusout( function() {
        if ( $( this ).html() == '' ) {
            $( this ).html( 'Search Clients' );
        }
    } )

    /*
    // Underline clicked menu
    $( 'a' ).click( function() {
        $( 'a' ).css( 'text-decoration', 'none' );
        $( this ).css( 'text-decoration', 'underline' );
    } )
    */

})
