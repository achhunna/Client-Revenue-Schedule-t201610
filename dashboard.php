<div class="wrapper">
    <div class="input_box client_box" contenteditable>Search Clients</div>
    <button>Search</button>
</div>

<div class="wrapper">

    <div class="input_section">
        <span class="input_heading">Client count summary</span>
    </div>
    <table class="list">
        <thead>
            <tr>
                <th class="clear">

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

                foreach ( $months_array as $month ) { ?>
                <th>
                    <?php
                    echo month_number( $month );
                    if ( $month == end( $months_array ) ) {
                        echo ' (Curr)';
                    }
                    ?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $count_array = array( 'Beginning', 'Adds', 'Terminations', 'Ending' );

                foreach ( $count_array as $count ) { ?>
                    <tr>
                        <td class="clear"><?php echo $count; ?></td>

                        <?php
                        foreach ( $months_array as $month ) { ?>
                            <td> <?php echo $count . ': ' . $month; ?> </td>
                        <?php }

                        ?>
                    </tr>
                <?php }
            ?>

            <tr class="no_border">
                <td colspan="6"></td>
            </tr>

            <?php $billing_array = array( 'Active clients invoices', 'Sales order booked', 'Total monthly billings' );

                foreach ( $billing_array as $billing ) { ?>
                    <tr>
                        <td class="clear"><?php echo $billing; ?></td>

                        <?php
                        foreach ( $months_array as $month ) { ?>
                            <td> <?php echo $billing . ': ' . $month; ?> </td>
                        <?php }

                        ?>
                    </tr>
                <?php }
            ?>

            <tr class="no_border">
                <td colspan="6"></td>
            </tr>

            <?php $aging_array = array( 'Aging', '0-30', '31-60', '61-90', 'Older' );

                foreach ( $aging_array as $aging ) { ?>
                    <tr>
                        <td class="clear"><?php echo $aging; ?></td>

                        <?php
                        foreach ( $months_array as $month ) { ?>
                            <td> <?php echo $aging . ': ' . $month; ?> </td>
                        <?php }

                        ?>
                    </tr>
                <?php }
            ?>
        </tbody>
    </table>

</div>
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

            $results = select_query_log( 'log_date, change_type, table_change, display_name', $change_log_table );

            if ( $results ) {
                foreach ( $results as $row ) {
                    ?>
                <tr>
                        <?php
                        foreach ( $row as $key => $value ) {
                        ?>
                    <td contenteditable>
                            <?php
                            // Number format for $ value
                            if ( $key == 'transaction_value' ) {
                                echo number_format( $value, 2 );
                            } else {
                                echo $value;
                            }
                            ?>
                    </td>
                        <?php } ?>
                    <td align="center"><button>Viewer</button></td>
                </tr>
                    <?php }
            } ?>


        </tbody>
    </table>
</div>

<!-- Leave blank for spacing -->
<div class="wrapper"></div>
