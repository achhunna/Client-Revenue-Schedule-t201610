<?php include( 'header.php' ); ?>
<div class="container">
<?php
// Calculate the path using $_SERVER for redirect
$path = substr( $_SERVER[ 'REQUEST_URI' ], 0, strrpos( $_SERVER[ 'REQUEST_URI' ], '/' ) + 1 );
// Check for user login cookie
if ( isset( $_COOKIE[ 'tally_user_id' ] ) ) {
    /* PHP redirect page */
    header( 'Location: ' . $path );
    exit();
} else {
    // Load tally-functions
    include( 'tally-functions.php' );

    if ( isset( $_POST['username'] ) && ! empty( $_POST['username'] ) && isset( $_POST['password'] ) && ! empty( $_POST['password'] ) ) {
        $user = wp_tally_login( $_POST['username'], $_POST['password']);
        if ( ! empty( $user ) ) {
            set_cookie( $user );
            header( 'Location: ' . $path );
            exit();
        } else {
            echo 'Login Failed.';
        }
    }
}

?>
<div class="wrapper">
    <div class="heading">Tally</div>
    <div class="wrapper login_box">

        <form id="login_form" method="post">
            <div class="input_section">
                <div class="input_heading">Username</div>
                <input type="text" class="input_box login_box" name="username">
            </div>
            <div class="input_section">
                <div class="input_heading">Password</div>
                <input type="password" class="input_box login_box" name="password">
            </div>
            <div class="input_section">
                <button>Login</button>
            </div>
        </form>

    </div>
</div>
<?php include( 'footer.php' ); ?>
