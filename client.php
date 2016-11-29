<div class="wrapper">
    <div class="input_box client_box" contenteditable>Search Clients</div>
    <button onclick="update_client()">Search</button>
    <button onclick="delete_client()">Delete</button>
    <button onclick="delete_all()">Delete All</button>
</div>
<span id="error"></span>

<div class="wrapper" id="client_info">

    <?php
    // acctg_invoice_clients table query
    $results = select_query_client( $client_id, 'meta_key, meta_value', $client_table );

    if ( $results ) {

        $output = $results[0];
        $output_array = parse_client_meta( $output );

        // Insert client_id into output array
        $client_array = array ( 'client_id' => $client_id );
        $output_array = $client_array + $output_array;

        foreach ( $output_array as $key => $value ) { ?>
    <div class="input_section">
        <span class="input_heading"><?php echo ucfirst( $key ); ?></span>
        <div class="input_box no_edit" id="<?php echo $key; ?>"><?php echo $value; ?></div>
    </div>
        <?php }
    }
    ?>

</div>

<div class="wrapper" id="client_deal">

    <?php
    // acctg_invoice_clients_key_dates table query

    $results = select_query_client( $client_id, 'date_deal_done, date_term', $client_deals_table);

    if ( $results ) {
        foreach ( $results[0] as $key => $value ) { ?>
        <div class="input_section">
            <span class="input_heading"><?php echo ucfirst( $key ); ?></span>
            <div class="input_box no_edit" id="<?php echo $key; ?>"><?php echo $value; ?></div>
        </div>
        <?php }
    } ?>

</div>

<div class="wrapper">
    <div class="input_section">
        <span class="input_heading">Client Schedules</span>
    </div>
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
        <tbody id="schedule_table">

                <?php
                // acctg_invoice_client_schedules table query

                $results = select_query_client( $client_id, 'date_only, transaction_type, transaction_product_variation, transaction_value, transaction_note, acctg_invoice_client_schedules_id', $schedule_table );
                $counter = 0;

                if ( $results ) {
                    foreach ( $results as $row) {
                    ?>
                <tr>
                        <?php
                        foreach ( $row as $key => $value ) {
                        ?>
                    <td>
                            <?php
                            // Number format for $ value
                            if ( $key == 'transaction_value' ) {
                                echo number_format( $value, 2 );
                            } else if ( $key == 'acctg_invoice_client_schedules_id' ) {
                                $display_array = json_encode( $row );
                                //echo $array_pass;
                                echo '<script>var client_counter_' . $counter . '= ' . $display_array . ';</script>';
                            ?>
                                <button class="button_center" onclick="show_display_array( 'Client Edit', <?php echo 'client_counter_' . $counter; ?> )">Edit</button>
                            <?php
                            } else {
                                echo $value;
                            }
                            ?>
                    </td>
                        <?php
                        $counter++;
                        }
                        ?>

                </tr>
                    <?php }
                } ?>

        </tbody>
    </table>
</div>

<!-- Leave blank for spacing -->
<div class="wrapper"></div>
