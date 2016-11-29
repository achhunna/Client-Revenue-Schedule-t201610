<?php

include( 'header.php' );

//echo dirname(__FILE__); // File path

// Check for user login cookie
if ( ! isset( $_COOKIE['tally_user_id'] ) ) {
    // PHP redirect page
    header( 'Location: login.php' );
    exit();
} else {
    // Assign user logged in to mc_user_id variable
    $mc_user_id = $_COOKIE['tally_user_id'];
}

// Load functions
include( 'functions.php' );

// Check if $wpdb is null
if ( is_null( $wpdb) ) {
    echo '$wpdb is Null';
}

?>
<div class="container">

    <div>
        <div class="heading">Tally</div>

        <!-- Display logged in user -->
        <div class="heading_user">
            <div class="user_id">Hello, <b><?php echo select_query_user( $mc_user_id ); ?></b></div>
            <a href="" class="menu_link log_out" onclick="delete_cookie( 'tally_user_id' )">Log out</a>
        </div>
    </div>

    <!-- Menu bar -->
    <div class="menu">
        <a href="#" id="dashboard_link" class="menu_link" onclick="switch_partial( 'dashboard' )">Dashboard</a>
        <a href="#" id="client_detail_link" class="menu_link" onclick="switch_partial( 'client_detail' )">Client Detail</a>
        <a href="#" id="csv_upload_link" class="menu_link" onclick="switch_partial( 'csv_upload' )">Upload CSV</a>

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

    <div class="overlay">
        <div class="wrapper audit_box">
            <div class="title_box">
                <span class="title"></span>
                <button class="right" onclick="toggle_overlay()">&#x2715;</button>
            </div>
            <div id="overlay_box"></div>
        </div>
    </div>

<?php include( 'footer.php' ); ?>
