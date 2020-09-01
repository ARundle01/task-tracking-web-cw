<?php
    session_start();
    ?>

<!DOCTYPE html>
<html>
    <head>
        <title>Change Preferences</title>
        <?php include "pref_check.php" ?>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <div class="row">
            <div class="col-3"><h1>Change Preferences</h1></div>
        </div>
        <form name="change_pref" action="/mydir/index.php/change_colourmode" method="post">
            <div class="row">
                <div class="col-1">
                    <label for="light_mode">Light Mode</label><input type="radio" name="colourmode" id="light_mode" value="light_mode">
                </div>
                <div class="col-1">
                    <label for="dark_mode">Dark Mode</label><input type="radio" name="colourmode" id="dark_mode" value="dark_mode">
                </div>
                <div class="col-2">
                    <label for="horror_mode">Horror</label><input type="radio" name="colourmode" id="horror_mode" value="horror_mode">
                </div>
            </div>
            <div class="row">
                <div class="col-1">
                    <input type="submit" value="Ok">
                </div>
            </div>
        </form>
        <form action="/mydir/index.php/task">
            <div class="col-1">
                <input type="submit" value="Go Back">
            </div>
        </form>
    </body>
</html>
