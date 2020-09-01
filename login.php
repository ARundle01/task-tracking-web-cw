<?php
    session_start();
    ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login to an Account</title>
        <link rel="stylesheet" type="text/css" href="/mydir/css/login.css">
    </head>
    <body>
        <h1>Login to an Account</h1>
        <div class="row">
            <div class="column-12">
                <form name="loginForm" method="post" action="http://example.ac.uk/mydir/index.php/login">
                    <label for="username">Username:</label><br><input type="text" id="username" name="username" required><br>
                    <label for="pword">Password:</label><br><input type="password" id="pword" name="pword" required><br>

                    <br><input type="submit" id="login_ok" name="login_ok" value="OK">
                </form>
                <form action="register">
                    <input type="submit" value="Register">
                </form>
            </div>
        </div>

        <?php
        $table_hash = $_POST["table_hash"];
        $username = $_POST["username"];
        $hash = $_POST["password"];

        if (!empty($hash) and !empty($table_hash)) {
            if ($hash === $table_hash) {
                $_SESSION["user"] = $username;
                $_SESSION["user_id"] = $model->getUserId($username);
                
                header('location: /mydir/index.php/task');
            } else {
                echo "<br> Password is incorrect. Try again.";
            }
        }
        ?>
    </body>
</html>