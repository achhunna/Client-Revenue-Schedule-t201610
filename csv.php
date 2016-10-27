<div class="wrapper">
    <form method="post" enctype="multipart/form-data">
        <div class="input_section">
            <div class="input_heading">Select file to upload</div>
            <input type="file" name="csv_file" accept=".csv">
            <button>Submit</button>
        </div>
    </form>

    <?php

    // Process uploaded CSV file
    if ( isset( $_FILES ) && $_FILES['csv_file']['type'] == 'text/csv' && $_FILES['csv_file']['size'] != 0 ) {

        $file        = $_FILES['csv_file']['tmp_name'];
        $filename    = $_FILES['csv_file']['name'];
        $table_name  = substr( $filename, 0, strrpos( $filename, '.' ) );
        $file_handle = fopen( $file, 'r' );

        // Parse into meta_key and meta_value for client_table
        $csv_array   = parse_meta_client( read_csv( $file_handle ), $table_name );

        ?>


        <div class="input_section">
            <span class="input_heading">Data from CSV uploaded</span>
        </div>
        <table class="list">
            <?php
            for ( $i = 0; $i < count( $csv_array ); $i++ ) {
                if ( $i == 0 ) { ?>
                <thead>
                    <tr>
                    <?php
                    foreach ( $csv_array[ $i ] as $item ) { ?>
                        <th> <?php echo $item; ?> </th>
                    <?php } ?>
                    </tr>
                </thead>

                <?php } else { ?>
                <tbody>
                    <tr>
                        <?php
                        foreach ( $csv_array[ $i ] as $item ) { ?>
                            <td> <?php echo $item; ?> </td>
                        <?php } ?>
                    </tr>
                </tbody>
                <?php
                }


                // Insert data into table
                switch ( $table_name ) {
                    case $client_table:
                        // Insert data into client table
                        $reference_id = insert_query_client( $csv_array[ $i ][0], $csv_array[ $i ][1], $csv_array[ $i ][2] );

                        break;
                    case $client_deals_table:
                        // Insert data into deals table
                        $reference_id = insert_query_client_deals( $csv_array[ $i ][0], $csv_array[ $i ][1], $csv_array[ $i ][2], $csv_array[ $i ][3] );

                        break;
                    case $schedule_table:
                        // Insert data into schedule table
                        $reference_id = insert_query_schedule( $csv_array[ $i ][0], $csv_array[ $i ][1], $csv_array[ $i ][2], $csv_array[ $i ][3],  $csv_array[ $i ][4], $csv_array[ $i ][5] );

                        break;
                    }

                    // Make update to log table
                    csv_log_change( $table_name, $reference_id, $csv_array[ $i ] );

                
            } ?>
        </table>
        <?php
            // Testing out last_reference_id function output
            //echo '<br><br>Reference_id last value: ' . last_reference_id( $table_name );

            fclose( $file_handle );
        }
        ?>
</div>

<!-- Leave blank for spacing -->
<div class="wrapper"></div>
