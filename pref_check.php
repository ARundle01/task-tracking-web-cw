<?php
    session_start();

    if (!isset($_SESSION['user'])) {
        header('location: /mydir/index.php/login');
    } else {
        $user = $_SESSION['user'];
    }

    if (isset($_COOKIE["colourmode"]) && $_COOKIE['colourmode'] == "dark_mode") {
        echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"/mydir/css/darkmode.css\">";
    } elseif (isset($_COOKIE["colourmode"]) && $_COOKIE['colourmode'] == "light_mode") {
        echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"/mydir/css/task.css\">";
    } else {
        echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"/mydir/css/horror.css\">";
    }
