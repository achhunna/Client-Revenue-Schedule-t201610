// Number format function
function number_format( input ) {
    return input.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
}

// Capitalize first letter
function ucfirst( input ) {
    return input.substr(0,1).toUpperCase() + input.substr(1);
}

// Switch partial based on selection
function switch_partial( selection ) {
    // Hide all partial divs
    $( '.partial' ).hide();
    // Show selection div based on id
    $( '#' + selection ).show();
}

// Update client_id query
function update() {
    client_id = $('#client_id').html();

    // Ajax call to update client
    $.ajax({
        type: 'post',
        url: 'ajax.php',
        data: '&action=update&client_id=' + client_id,
        cache:  false,
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
    for ( property in parse_obj ) {

        // Parse string inside into JSON object
        var parse_obj_data = $.parseJSON( parse_obj[ property ] );
        // Create placeholder to join tags
        var placeholder = '';

        // Route property to correct section
        if( property === 'client_schedule' ) {
            /*
            // Parse string inside client_schedule into JSON object
            var parse_obj_schedule = $.parseJSON( parse_obj[ property ] );

            // Create table_string to join tags
            var table_string = '';

            for ( rows in parse_obj_schedule ) {
                table_string += '<tr>';

                for ( field in parse_obj_schedule[ rows ] ) {
                    var td_content = parse_obj_schedule[ rows ][ field ];
                    // Append each value into table
                    if ( field === 'transaction_value' ) {
                        td_content = number_format( td_content );
                    }
                    table_string += '<td contenteditable>' + td_content + '</td>';
                }

                table_string += '<td align="center"><button>Edit</button></td></tr>';
            }
            // Update schedule_table text with complete string
            $( '#schedule_table' ).html( table_string );
            */
            //console.log( 'schedule: ' + parse_obj_data );


            for ( rows in parse_obj_data ) {
                placeholder += '<tr>';

                for ( item in parse_obj_data[ rows ] ) {
                    var td_content = parse_obj_data[ rows ][ item ];
                    // Append each value into table
                    if ( item === 'transaction_value' ) {
                        td_content = number_format( td_content );
                    }
                    placeholder += '<td contenteditable>' + td_content + '</td>';
                }

                placeholder += '<td align="center"><button>Edit</button></td></tr>';

            }

            // Update schedule_table text with complete string
            $( '#schedule_table' ).html( placeholder );

        } else {

            for ( rows in parse_obj_data ) {
                //console.log( 'info: ' + rows + ': ' + parse_obj_data[ rows ] );
                placeholder += '<div class="input_section"><span class="input_heading">' + ucfirst( rows ) + '</span><div class="input_box" id="' + rows + '" contenteditable>' +  parse_obj_data[ rows ] + '</div></div>';
            }

            // Update client_info text with complete string
            $( '#' + property ).html( placeholder );
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
