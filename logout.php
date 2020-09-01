<?php
    session_start();
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_unset();
        session_destroy();
    }
    header("Refresh: 5; url = /mydir/index.php/login");
    ?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="/mydir/css/task.css">
        <title>Logged out!</title>
    </head>
    <body>
        <?php // PHP script to redirect to login page if not logged in.
            if (!isset($_SESSION['user'])) {
                header('location: /mydir/index.php/login');
            } else {
                $user = $_SESSION['user'];
            }
        ?>
        <h1>Successfully logged out!</h1>
    </body>
</html>
