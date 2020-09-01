<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Register new Account</title>
        <link rel="stylesheet" type="text/css" href="/mydir/css/login.css">
    </head>
    <body>
        <h1 class="register_title">Register a new Account</h1>
        <div class="row">
            <div class="column-1">
                <form class="register_form" action="register" method="post">
                    <label for="username">Username:</label><br><input type="text" id="username" name="username" required><br>
                    <label for="pword">Password:</label><br><input type="password" id="pword" name="pword" required><br>

                    <br><input type="submit" id="register_ok" name="register_ok" value="OK">
                </form>
                <form action="login">
                    <input type="submit" value="Login">
                </form>
            </div>
        </div>

        <?php
            if (isset($_POST["register_ok"])) {
                if ((empty($_POST["username"]) or empty($_POST["pword"])) !== true) {
                    $username = $_POST["username"];
                    $salt = openssl_random_pseudo_bytes(8);
                    $concat = $salt . $_POST["pword"] . PEPPER;
                    $hash = md5($concat);

                    $model->insertNewUser($username, $hash, $salt);
                }
            }
        ?>
    </body>
</html>
