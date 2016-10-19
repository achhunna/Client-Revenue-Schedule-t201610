<?php

include( 'header.php' );

//echo dirname(__FILE__); // File path

// Check for user login cookie
if ( ! isset( $_COOKIE['tally_user_id'] ) ) {
    // PHP redirect page
    header( 'Location: login.php' );
    exit();
} else {
    // assign user logged in to mc_user_id variable
    $mc_user_id = $_COOKIE['tally_user_id'];
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
        <div class="user_id">Hello, <b><?php echo select_query_user( $mc_user_id ); ?></b></div>
        <a href="" class="menu_link log_out" onclick="delete_cookie( 'tally_user_id' )">Log out</a>
    </div>

    <!-- Menu bar -->
    <div class="menu">
        <a href="#" class="menu_link" onclick="switch_partial( 'client_detail' )">Client Detail</a>
        <a href="#" class="menu_link" onclick="switch_partial( 'dashboard' )">Dashboard</a>
        <a href="#" class="menu_link" onclick="switch_partial( 'csv_upload' )">Upload CSV</a>
    </div>



    <!-- Dashboard -->
    <div id="dashboard" class="partial">
        <h2>Dashboard</h2>

        <?php include( 'dashboard.php' ) ?>
    </div>

    <!-- Client detail -->
    <div id="client_detail" class="partial">
        <h2>Client Detail</h2>

        <?php include( 'client.php' ); ?>
    </div>

    <!-- CSV upload -->
    <div id="csv_upload" class="partial">
        <h2>Upload CSV</h2>

        <?php include( 'csv.php' ) ?>
    </div>

<?php include( 'footer.php' ); ?>
