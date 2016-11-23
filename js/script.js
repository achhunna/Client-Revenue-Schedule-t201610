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
function update_client() {

    // Get client name
    client_name = $( '.client_box' ).text();

    // Ajax call to update client
    $.ajax({
        type: 'post',
        url: 'ajax.php',
        data: '&action=update_client&client_name=' + client_name,
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
        var counter = 0;
        var client_counter_ = [];

        // Route property to correct section
        if( property === 'client_schedule' ) {

            for ( rows in parse_obj_data ) {
                placeholder += '<tr>';

                for ( item in parse_obj_data[ rows ] ) {
                    var td_content = parse_obj_data[ rows ][ item ];
                    // Append each value into table
                    if ( item != 'acctg_invoice_client_schedules_id' ) {
                        if ( item === 'transaction_value' ) {
                            td_content = number_format( td_content );
                        }
                        placeholder += '<td>' + td_content + '</td>';
                    }

                }

                client_counter_[ counter ] = parse_obj_data[ rows ];

                placeholder += '<td><button id="client_button_' + counter + '">Edit</button></td></tr>';

                counter += 1;
            }

            // Update schedule_table text with complete string
            $( '#schedule_table' ).html( placeholder );

            // Add click event to buttons
            for ( var i = 0; i <= counter; i++ ) {
                // Event listner in a closure
                ( function(i) {
                    $( '#client_button_' + i ).click( function() {
                        show_display_array( 'Client Edit', client_counter_[i] );
                    });
                })(i);
            }

        } else {

            for ( rows in parse_obj_data ) {
                //console.log( 'info: ' + rows + ': ' + parse_obj_data[ rows ] );
                placeholder += '<div class="input_section"><span class="input_heading">' + ucfirst( rows ) + ' </span><div class="input_box" id="' + rows + '">' +  parse_obj_data[ rows ] + '</div><span id="error"></span></div>';
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
            if ( data ) {
                // Refresh page
                location.reload();
                //console.log( data );
            }
        }
    });
}

// Update schedule
function update_schedule( key_string ) {
    // Split string into array
    var key_array = key_string.split(',');
    // Create update array object
    var update_array = {};

    var table_name = 'acctg_invoice_client_schedules';

    // Gather update data
    for ( var key in key_array ) {
        //console.log( $( '#' + key_array[ key ] ).text() );
        update_array[ key_array[ key ] ] = $( '#' + key_array[ key ] ).text();
    }


    // Ajax call to update schedule
    $.ajax({
        type: 'post',
        url: 'ajax.php',
        data: { 'action': 'update_schedule', 'table_name': table_name, 'update_array': update_array },
        success: function( data ) {
            if ( data ) {
                // Refresh page
                //location.reload();
                console.log( data );
                // Update client table
                update_client();
            }
        }
    });

    // Toggle overlay
    toggle_overlay();


}

// Delete all function
function delete_all() {

    if ( confirm( 'Are you sure you want to delete?' ) ) {

        // Ajax call to delete all
        $.ajax({
            type: 'post',
            url: 'ajax.php',
            data: 'action=delete_all',
            success: function( data ) {
                // Refresh page
                location.reload();
            }
        });
    }
}


// Hide overlay div
function toggle_overlay() {
    $( '.overlay' ).toggle();
}

// Show display array content
function show_display_array( title, display_array ) {
    // Call toggle overlay
    toggle_overlay();

    $( '.title' ).html( title );

    var parse_display_array = display_array;

    var placeholder = '';
    var key_array = [];

    for ( var key in parse_display_array ) {

        //console.log( key + ': ' + parse_display_array[ key ] );
        placeholder += '<div class="input_section">';

        if ( title == 'Audit Log Viewer' ) {
            placeholder += '<span class="input_heading">' + key + '</span><div class="input_box" id="' + key + '">' + parse_display_array[ key ] + '</div>';
        } else {
            if ( key != 'acctg_invoice_client_schedules_id' ) {
                placeholder += '<span class="input_heading">' + key + '</span><div class="input_box"  id="' + key + '"contenteditable>' + parse_display_array[ key ] + '</div>';
            } else {
                placeholder += '<div class="input_box" id="' + key + '">' + parse_display_array[ key ] + '</div>';
            }
        }
        placeholder += '</div>';

        // Append to key array
        key_array.push( key );

    }

    if ( title == 'Client Edit') {

        // Create update button and pass array
        placeholder += '<button onclick="update_schedule( \'' + key_array +'\' )">Update</button>';

    }

    // Update div with placeholder
    $( '#overlay_box' ).html( placeholder );
    // Hide schedule id div
    $( '#acctg_invoice_client_schedules_id').hide();

}

// Delete cookie
function delete_cookie( cookie_name ) {
    document.cookie = cookie_name + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
    location.reload();
}


// Document ready
$( document ).ready( function() {

    // Load default div partial
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

    /* Overlay callback functions */
    // Hide overlay div
    $( '.overlay' ).hide();

    $( '.overlay' ).click( function (e) {
        $( this ).hide();
    } )

    $( '.audit_box' ).click( function (e) {
        return false;
    } )

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
