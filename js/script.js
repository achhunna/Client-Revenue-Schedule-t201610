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
        data: '&action=update&client_id=' + client_id,
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
    // Parse string into data to JSON object
    var parse_obj = $.parseJSON( data );
    // Iterate through parse_data object to update DOM
    for ( field in parse_obj ) {
        //console.log( field + ': ' + parse_data[ field ] );
        if( field === 'schedule_array' ) {
            // Parse string inside schedule_array into JSON object
            var parse_obj_schedule = $.parseJSON( parse_obj[ field ] );

            // Create table_string to join tags
            var table_string = '';

            for ( rows in parse_obj_schedule ) {
                table_string += '<tr>';
                for ( field in parse_obj_schedule[ rows ] ) {
                    table_string += '<td contenteditable>';
                    var td_content = parse_obj_schedule[ rows ][ field ];
                    //console.log( rows + ') ' + field + ': ' + td_content );
                    table_string += td_content;
                    table_string += '</td>';
                }
                table_string += '<td align="center"><button>Edit</button></td>';
                table_string += '</tr>';
            }
            // Update schedule_table text with complete string
            $( '#schedule_table' ).html( table_string );

        } else {
            $( '#' + field ).html( parse_obj[ field ] );
        }
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
