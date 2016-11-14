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

    // Hide all underline
    $( '.menu_link' ).css( 'text-decoration', 'none' );
    // Underline selected text
    $( '#' + selection + '_link' ).css( 'text-decoration', 'Underline' );
}

// Check browser support for HTML5 File API
function file_supported() {
    var isCompatible = false;
    if ( window.File && window.FileReader && window.FileList && window.Blob ) {
        isCompatible = true;
    }
    return isCompatible;
}

// Update client_id query
function update() {
    client_id = $('#client_id').html();

    // Ajax call to update client
    $.ajax({
        type: 'post',
        url: 'ajax.php',
        data: '&action=update_client&client_id=' + client_id,
        cache:  false,
        success: function( data ) {

            if( !data ) {
                $( '#error' ).html('Not Found');
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
                placeholder += '<div class="input_section"><span class="input_heading">' + ucfirst( rows ) + '</span><div class="input_box" id="' + rows + '" contenteditable>' +  parse_obj_data[ rows ] + '</div><span id="error"></span></div>';
            }

            // Update client_info text with complete string
            $( '#' + property ).html( placeholder );
        }
    }

}

// Upload CSV
function upload_csv() {

    // Check if file is selected
    if ( $( '#csv_file' ).val() != '' ) {
        if ( !file_supported() ) {
            alert( 'The File APIs are not fully supported in this browser!' );
        } else {
            // Remove previous csv output div
            $( '#csv_output' ).html('');

            // Parse CSV file to display in table
            var data = null;
            var csv_file = $( 'input#csv_file' ).prop( 'files' )[0];
            var reader = new FileReader();


            // HTML5 FileReader to display CSV file
            reader.readAsText( csv_file );
            // Get filename
            reader.fileName = csv_file.name;
            reader.onload = function ( e ) {
                var csv_data = e.target.result;
                // Convert data to array
                csv_array = $.csv.toArrays( csv_data );
                // Extract table name from filename
                table_name = reader.fileName.substr( 0, reader.fileName.indexOf( '.' ) );

                if ( csv_array && csv_array.length > 0 ) {

                    var heading = '<thead>';
                    var body = '<tbody>';

                    // Update div with csv_array
                    for ( key in csv_array ) {

                        heading += '<tr>';
                        body += '<tr>';

                        for ( item in csv_array[ key ] ) {
                            // For table heading
                            if ( key === '0' ) {
                                heading += '<th>' + csv_array[ key ][ item ] + '</th>';
                            } else {
                                body += '<td>' + csv_array[ key ][ item ] + '</td>';
                            }

                        }
                        if ( key === '0' ) {
                            heading += '</tr>';
                        }
                        body += '</tr>';

                    }
                    heading += '</thead>';
                    body += '</tbody>';

                    // Append head and body to table
                    $( '#csv_output' ).append( heading + body );

                    // Unhide CSV output container
                    $( '#csv_output_container' ).show();
                }

            };
            reader.onerror = function() {
                alert( 'Unable to read ' + csv_file.fileName );
            };

        }

        // Reset input file
        $( '#csv_file' ).replaceWith( $( '#csv_file' ).val( '' ).clone( true ) );
    }
}

// Cancel CSV upload
function reset_csv_upload() {

    // Hide CSV output container
    $( '#csv_output_container' ).hide();
    // Remove csv output div
    $( '#csv_output' ).html('');
}


// Post CSV file
function post_csv_upload() {

    // Ajax call to upload CSV
    $.ajax({
        type: 'post',
        url: 'ajax.php',
        data: { 'action': 'parse_csv', 'table_name': table_name, 'csv_array': csv_array },
        success: function( data ) {
            // Refresh page
            location.reload();
        }
    });
}

// Delete cookie
function delete_cookie( cookie_name ) {
    document.cookie = cookie_name + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
    location.reload();
}


// Document ready
$( document ).ready( function() {

    // Load default div
    switch_partial( 'dashboard' );


    // Client search box
    $( '.client_box' ).click( function() {
        $( this ).html( '' );
    } )
    $( '.client_box' ).focusout( function() {
        if ( $( this ).html() == '' ) {
            $( this ).html( 'Search Clients' );
        }
    } )

    // Hide CSV output container
    $( '#csv_output_container' ).hide();


    /*
    // Underline clicked menu
    $( 'a' ).click( function() {
        $( 'a' ).css( 'text-decoration', 'none' );
        $( this ).css( 'text-decoration', 'underline' );
    } )
    */

    // AjaxForm to bind 'csv_form' to callback function, when it is used
    /*
    $( '#csv_form' ).ajaxForm( function () {
        alert( 'test' );
    } );
    */

    var csv_array = '';
    var table_name = '';

})
