<div class="wrapper">
    <form method="post" enctype="multipart/form-data">
        <div class="input_section">
            <div class="input_heading">Select file to upload</div>
            <input type="file" name="csv_file" accept=".csv">
            <button onclick="switch_partial( 'csv_upload' )">Submit</button>
        </div>
    </form>

    <?php

    // Process uploaded CSV file
    if ( isset( $_FILES ) && $_FILES['csv_file']['type'] == 'text/csv' && $_FILES['csv_file']['size'] != 0 ) {

        $file        = $_FILES['csv_file']['tmp_name'];
        $filename    = $_FILES['csv_file']['name'];
        $table_name  = substr( $filename, 0, strrpos( $filename, '.' ) );
        $file_handle = fopen( $file, 'r' );

        $csv_array   = parse_meta_client( read_csv( $file_handle ), $table_name );

        echo 'Array inserted into database:' . '<br>';
        print_r( $csv_array );
        echo '<br><br>';

        for ( $i = 1; $i < count( $csv_array ); $i++ ) {
            switch ( $table_name ) {
                case $client_table:
                    // Insert data from CSV
                    //insert_query_client( $csv_array[ $i ][0], $csv_array[ $i ][1], $csv_array[ $i ][2] );

                    // Print items
                    foreach ( $csv_array[ $i ] as $item ) {
                        echo $item . '<br>';
                    }

                    break;
                case $client_deals_table:
                    // Insert data from CSV
                    //insert_query_client_deals( $csv_array[ $i ][0], $csv_array[ $i ][1], $csv_array[ $i ][2], $csv_array[ $i ][3] );

                    // Print items
                    foreach ( $csv_array[ $i ]  as $item ) {
                        echo $item . '<br>';
                    }

                    break;
                case $schedule_table:
                    // Insert data from CSV
                    //insert_query_schedule( $csv_array[ $i ][0], $csv_array[ $i ][1], $csv_array[ $i ][2], $csv_array[ $i ][3],  $csv_array[ $i ][4], $csv_array[ $i ][5] );

                    // Print items
                    foreach ( $csv_array[ $i ]  as $item ) {
                        echo $item . '<br>';
                    }

                    break;
                }
        }

        fclose( $file_handle );
    }
    ?>
</div>
