<?php
    session_start();

    if (isset($_POST['colourmode'])) {
        if (!isset($_COOKIE['colourmode'])) {
            $preference = $_POST["colourmode"];
            setcookie("colourmode", $preference, time() + 31556926, "/");
        } elseif ($_COOKIE['colourmode'] !== $_POST['colourmode'] && isset($_COOKIE['colourmode'])) {
            $preference = $_POST["colourmode"];
            setcookie("colourmode", $preference, time() + 31556926, "/");
        }
    }

    header("location: /mydir/index.php/change_preferences");

