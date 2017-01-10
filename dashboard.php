<?php
// For Dashboard
$current_month = date( 'm' );
?>
<!-- Client count summary -->
<div class="wrapper">
    <div class="input_section">
        <span class="input_heading">Client count summary</span>
    </div>
    <table class="list">
        <thead>
            <tr>
                <th class="clear">
                <!-- Leave blank -->
                </th>
                <?php
                // Define months array
                $months_array = array(
                                $current_month - 4,
                                $current_month - 3,
                                $current_month - 2,
                                $current_month - 1,
                                $current_month
                            );
                // Iterate through months
                foreach ( $months_array as $month ) { ?>
                <th>
                    <?php
                    echo month_number( $month );
                    // For current month
                    if ( $month == end( $months_array ) ) {
                        echo ' (Curr)';
                    }
                    ?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $count_array = array( 'Beginning', 'Adds', 'Terminations', 'Ending' );
                // Iterate through each client count row
                foreach ( $count_array as $count ) { ?>
                    <tr>
                        <td class="clear"><?php echo $count; ?></td>
                        <?php
                        // Monthly data
                        foreach ( $months_array as $month ) { ?>
                            <td> <?php
                                 if ( $count == 'Beginning' ) {
                                     echo $month;
                                 } else if ( $count == 'Adds' ) {
                                     echo '1';
                                 } else if ( $count == 'Terminations' ) {
                                     echo '-1';
                                 } else {
                                     echo ( $month + 1 );
                                 }
                                 ?>
                             </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            <tr class="no_border">
                <td colspan="6"></td>
            </tr>
            <?php $billing_array = array( 'Active clients invoices', 'Sales order booked', 'Total monthly billings' );
                // Iterate through each booking/billing row
                foreach ( $billing_array as $billing ) { ?>
                    <tr>
                        <td class="clear"><?php echo $billing; ?></td>
                        <?php
                        foreach ( $months_array as $month ) { ?>
                            <td> <?php echo $billing . ': ' . $month; ?> </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            <tr class="no_border">
                <td colspan="6"></td>
            </tr>
            <?php $aging_array = array( 'Aging', '0-30', '31-60', '61-90', 'Older' );
                // Iterate through each aging row
                foreach ( $aging_array as $aging ) { ?>
                    <tr>
                        <td class="clear"><?php echo $aging; ?></td>
                        <?php
                        foreach ( $months_array as $month ) { ?>
                            <td> <?php echo $aging . ': ' . $month; ?> </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
        </tbody>
    </table>
</div>
<!-- Change log listing -->
<div class="wrapper">
    <div class="input_section">
        <span class="input_heading">Change Log (last 100)</span>
    </div>
    <table class="list">
        <thead>
            <tr>
                <th>Date</th>
                <th>Event</th>
                <th>Client</th>
                <th>User</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Query log table with desc sort option
            $results = select_query_log_sort( 'log_date, change_type, table_change, reference_id, mc_user_id, log_id', $change_log_table, 'log_id', 'desc' );
            $counter = 0;
            $log_id = 0;

            if ( $results ) {
                foreach ( $results as $row ) {
                ?>
                <tr>
                        <?php
                        foreach ( $row as $key => $value ) {
                            if ( $key == 'table_change' ) {
                                // Store table value
                                $table = $value;
                                continue;
                            }
                        ?>
                    <td>
                            <?php
                            // Conditional output depending on key
                            if ( $key == 'log_date' ) {
                                // Convert UTC date to local time
                                $utc = strtotime( $value . ' UTC' );
                                echo date( 'Y-m-d H:i:s', $utc );
                            } else if ( $key == 'transaction_value' ) {
                                // Number format for $ value
                                echo number_format( $value, 2 );
                            } else if ( $key == 'log_id' ) {
                                // Prepare audit viewer for each log id
                                $log_id = intval( $value );
                                $display_array = json_encode( select_query_log_id( 'log_date, table_change, change_type, field_change, old_value, new_value', $change_log_table, $log_id )[0] );
                                echo '<script>var dashboard_counter_' . $counter . '= ' . $display_array . ';</script>';
                                ?>
                                <button class="button_center" onclick="show_display_array( 'Audit Log Viewer', <?php echo 'dashboard_counter_' . $counter; ?> )">Viewer</button>
                                <?php
                            } else if ( $key == 'reference_id' ) {
                                // Get client name
                                // Store reference_id
                                $reference_id = $value;
                                $client_id = json_decode( json_encode( select_query_table_id( $reference_id, 'client_id', $table )[0] ), true )[ 'client_id' ];
                                echo select_query_client_name( $client_id, $client_table );
                            } else {
                                echo $value;
                            }
                            ?>
                    </td>
                        <?php } ?>
                </tr>
                <?php
                // Increment counter
                $counter++;
                // List check counter for 100
                if ( $counter == 100 ) break;
                }
            } ?>
        </tbody>
    </table>
</div>
