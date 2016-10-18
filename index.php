<?php

include( 'header.php' );

//echo dirname(__FILE__); // File path

// Check for user login cookie
if ( ! isset( $_COOKIE[ 'tally_user_id' ] ) ) {
    // PHP redirect page
    header( 'Location: login.php' );
    exit();
} else {
    // assign user logged in to mc_user_id variable
    $mc_user_id = $_COOKIE[ 'tally_user_id' ];
}

?>
<!--
<div class="sidenav">
    <div class="heading">Tally</div>
    <a href="#">Client</a>
    <a href="#">Dashboard</a>
</div>
-->
<?php
// Load tally-functions
include( 'tally-functions.php' );

// Check if $wpdb is null
if ( is_null( $wpdb) ) {
    echo '$wpdb is Null';
}

?>
<div class="container">

    <div class="heading">Tally</div>

    <!-- Display logged in user -->
    <div class="heading_user">
        Hello, <?php echo select_query_user( $mc_user_id ); ?>.<br>
        <a href="" onclick="delete_cookie( 'tally_user_id' )">Log out</a>
    </div>

    <!-- Menu bar -->
    <div class="menu">
        <a href="#" class="menu_link" onclick="show_dashboard()">Dashboard</a>
        <a href="#" class="menu_link" onclick="show_client()">Client Detail</a>
        <a href="#" class="menu_link" onclick="show_csv_upload()">Upload CSV</a>
    </div>



    <!-- Dashboard -->
    <div id="dashboard">
        <h2>Dashboard</h2>

        <div class="wrapper">
            <div class="input_box client_box" contenteditable>Search Clients</div>
            <button>Search</button>
        </div>

    </div>

    <!-- Client detail -->
    <div id="client_detail">
        <h2>Client Detail</h2>

        <div class="wrapper">

            <?php
            // acctg_invoice_clients table query

            $results = select_query_client( $client_id, 'meta_key, meta_value', $client_table );

            $output = $results[0];
            $output_array = parse_client_meta( $output );

            // Insert client_id into output array
            $client_array = array ( 'client-id' => $client_id );
            $output_array = $client_array + $output_array;

            foreach ( $output_array as $key => $value ) { ?>
                <div class="input_section">
                    <span class="input_heading"><?php echo ucfirst( $key ); ?></span>
                    <div class="input_box"  id="<?php echo $key; ?>" contenteditable><?php echo $value; ?></div>
                </div>
            <?php } ?>

        </div>

        <div class="wrapper">

            <?php
            // acctg_invoice_clients_key_dates table query

            $results = select_query_client( $client_id, 'date_deal_done, date_term', $client_deals_table);
            foreach ( $results[0] as $key => $value ) { ?>
                <div class="input_section">
                    <span class="input_heading"><?php echo ucfirst( $key ); ?></span>
                    <div class="input_box" id="<?php echo $key; ?>"><?php echo $value; ?></div>
                </div>
            <?php } ?>

        </div>

        <div class="wrapper">
            <table class="list">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Product</th>
                        <th>Amount</th>
                        <th>Notes</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td align="center"><button>Edit</button></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="wrapper">
            <button onclick="update();">Update</button>
            <button>Add</button>
            <button>Delete</button>
        </div>

        <div class="wrapper"></div>
    </div>

    <!-- CSV upload -->
    <div id="csv_upload">
        <h2>Upload CSV</h2>
        <div class="wrapper">
            <form method="post" enctype="multipart/form-data">
                Select CSV <input type="file" name="csv_file">
                <button>Submit</button>
            </form>

            <?php

            // Process uploaded CSV file
            if ( isset( $_FILES ) && $_FILES[ 'csv_file' ][ 'type' ] == 'text/csv' && $_FILES[ 'csv_file' ][ 'size' ] != 0 ) {

                $file = $_FILES[ 'csv_file' ][ 'tmp_name' ];
                $filename = $_FILES[ 'csv_file' ][ 'name' ];
                $table_name = substr( $filename, 0, strrpos( $filename, '.' ) );
                $file_handle = fopen( $file, 'r' );

                $csv_array = read_csv( $file_handle );

                /*
                foreach ( $csv_array as $row ) {
                    // CSV heading
                    foreach ( $csv_array[0] as $item ) {

                        echo $item . ':';
                    }
                    echo '<br>';

                    foreach ( $row as $item ) {

                        echo $item . ',';
                    }
                    echo '<br><br>';
                }
                */


                for ( $i = 1; $i < count( $csv_array ); $i++ ) {
                    switch ( $table_name ) {
                        case $client_table:

                            break;
                        case $client_deals_table:
                            // Insert data from CSV
                            //insert_query_client_deals( $csv_array[$i][0], $csv_array[$i][1], $csv_array[$i][2], $csv_array[$i][3] );

                            // Update the login
                            foreach ( $csv_array[$i]  as $item ) {
                                echo $item . '<br>';
                            }

                            break;
                        case $schedule_table:
                            // Insert data from CSV
                            insert_query_schedule( $csv_array[$i][0], $csv_array[$i][1], $csv_array[$i][2], $csv_array[$i][3],  $csv_array[$i][4], $csv_array[$i][5] );

                            break;
                    }
                }


                fclose( $file_handle );
            }
            ?>
        </div>
    </div>
<?php include( 'footer.php' ); ?>
