/**
 *
 *    JavaScript functions
 *
 */

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

// Delete cookie
function delete_cookie( cookie_name ) {
    document.cookie = cookie_name + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
    location.reload();
}

// Update client_id query
function update_client() {
    // Get client name
    client_name = $( '.client_box' ).text();
    // If update is not called from search box
    if ( client_name === 'Search Clients' ) {
        client_name = $( '#name' ).text();
    }
    // Ajax call to update client
    $.ajax({
        type: 'post',
        url: 'ajax.php',
        data: '&action=update_client&client_name=' + client_name,
        cache:  false,
        success: function( data ) {
            if( !data ) {
                $( '#error' ).html('Not Found');
                // Remove error after 1 secs
                setTimeout( function() {
                    $( '#error' ).html('');
                }, 1000);
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
                placeholder += '<td><button class="button_center" id="client_button_' + counter + '">Edit</button></td></tr>';
                counter += 1;
            }
            // Update schedule_table text with complete string
            $( '#schedule_table' ).html( placeholder );
            // Add click event to buttons
            for ( var i = 0; i <= counter; i++ ) {
                // Event listner in a closure
                ( function( i ) {
                    $( '#client_button_' + i ).click( function() {
                        show_display_array( 'Client Edit', client_counter_[ i ] );
                    });
                })(i);
            }
        } else {
            for ( rows in parse_obj_data ) {
                placeholder += '<div class="input_section"><span class="input_heading">' + ucfirst( rows ) + ' </span><div class="input_box no_edit" id="' + rows + '">' +  parse_obj_data[ rows ] + '</div><span id="error"></span></div>';
            }
            // Update client_info text with complete string
            $( '#' + property ).html( placeholder );
        }
    }
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
            //console.log( data );
            // Update client table
            update_client();
        },
        error: function( jqXHR, textStatus, errorThrown ) {
            console.log( errorThrown );
        }
    });
    // Toggle overlay
    toggle_overlay();
}

// Delete client by id
function delete_client() {
    if ( confirm( 'Do you want to delete the client?' ) ) {
        // Get client id
        client_id = $( '#client_id' ).html();
        // Ajax call to delete all
        $.ajax({
            type: 'post',
            url: 'ajax.php',
            data: 'action=delete_client&client_id=' + client_id,
            success: function( data ) {
                // Refresh page
                location.reload();
                //console.log( data );
            }
        });
    }
}

// Delete all function
function delete_all() {
    if ( confirm( 'Delete all records?' ) ) {
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
            placeholder += '<span class="input_heading">' + ucfirst( key ) + '</span><div class="input_box no_edit" id="' + key + '">' + parse_display_array[ key ] + '</div>';
        } else {
            if ( key != 'acctg_invoice_client_schedules_id' ) {
                placeholder += '<span class="input_heading">' + ucfirst( key ) + '</span><div class="input_box"  id="' + key + '"contenteditable>' + parse_display_array[ key ] + '</div>';
            } else {
                placeholder += '<div class="input_box no_edit" id="' + key + '">' + parse_display_array[ key ] + '</div>';
            }
        }
        placeholder += '</div>';
        // Append to key array
        key_array.push( key );
    }
    if ( title == 'Client Edit') {
        // Create update button and pass array
        placeholder += '<button class="button_center" onclick="update_schedule( \'' + key_array +'\' )">Update</button>';
    }
    // Update div with placeholder
    $( '#overlay_box' ).html( placeholder );
    // Hide schedule id div
    $( '#acctg_invoice_client_schedules_id').hide();

}

// Upload CSV
function upload_csv() {
    // Check if file is selected
    if ( $( '#csv_file' ).val() !== '' ) {
        if ( !file_supported() ) {
            alert( 'The File APIs are not fully supported in this browser!' );
        } else {

            // Get the filename
            var csv_file = $( '#csv_file' ).prop( 'files' )[0];
            // HTML5 FileReader object to display CSV file
            var reader = new FileReader();
            reader.readAsText( csv_file );
            // Get filename
            reader.fileName = csv_file.name;
            reader.onload = function ( file ) {
                var csv_data = file.target.result;
                // Convert data to array
                var csv_array = $.csv.toArrays( csv_data );
                // Extract table name from filename by removing extension
                var table_name = reader.fileName.substr( 0, reader.fileName.indexOf( '.' ) );

                if ( csv_array && csv_array.length > 0 ) {
                    // Update DOM with CSV table
                    $( '#csv_output' ).html( update_csv_dom( csv_array ) );
                    // Unhide CSV output container
                    $( '#csv_output_container' ).show();
                    // Callback function to post csv data on click event
                    $('#post_csv').click( function () {
                        post_csv_upload( table_name, csv_array );
                    } );
                }
            };
            reader.onerror = function() {
                alert( 'Unable to read ' + csv_file.fileName );
            };
            // Reset input file
            $( '#csv_file' ).replaceWith( $( '#csv_file' ).val( '' ).clone( true ) );
        }
    }
}

// Update DOM for CSV
function update_csv_dom( csv_array ) {
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

    return heading + body;
}

// Cancel CSV upload
function reset_csv_upload() {
    // Hide CSV output container
    $( '#csv_output_container' ).hide();

}

// Post CSV file
function post_csv_upload( table_name, csv_array ) {

    // Ajax call to upload CSV
    $.ajax({
        type: 'post',
        url: 'ajax.php',
        data: { 'action': 'parse_csv', 'table_name': table_name, 'csv_array': csv_array },
        success: function( data ) {
            if ( data ) {
                $('.input_heading').html('Uploading file...').css({'font-weight': 'bold', 'color': '#800000'});
                // Refresh page
                setTimeout(function() { location.reload(); }, 1000);
            }
        }
    });
}


// Define variables
//var csv_array, table_name;
/*
    Ready function loaded when DOM loaded
*/
$( document ).ready( function() {

    // Load default div partial
    switch_partial( 'dashboard' );

    // Client search box
    $( '.client_box' ).click( function() {
        $( this ).html( '' );
    } );
    $( '.client_box' ).focusout( function() {
        if ( $( this ).html() == '' ) {
            $( this ).html( 'Search Clients' );
        }
    } );
    $( '.client_box' ).keydown( function( e ) {
        // trap return key
        if ( e.keyCode === 13 ) {
            update_client();
            $( this ).html( 'Search Clients' );
            return false;
        }
    } );
    // Autocomplete client box for names
    $( '.client_box' ).autocomplete( {
        source: 'autocomplete.php',
        //source: client_names,
        minLength: 1,
    } );

    // trap return key on all input boxes
    $( '.input_box' ).keydown( function( e ) {
        if ( e.keyCode === 13 ) {
            $( this ).blur();
            return false;
        }
    } )

    // Hide CSV output container
    $( '#csv_output_container' ).hide();

    /* Overlay callback functions */
    // Hide overlay div
    $( '.overlay' ).hide();

    $( '.overlay' ).click( function (e) {
        $( this ).hide();
    } );

    $( '.audit_box' ).click( function (e) {
        return false;
    } );
})
