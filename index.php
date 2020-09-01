<?php
require_once "model.php";
session_start();

$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

switch ($url) {
    case "/mydir/index.php":
        if (!isset($_SESSION['user'])) {
            header('location: /mydir/index.php/login');
        } else {
            $user = $_SESSION['user'];
            header('location: /mydir/index.php/task');
        }
        break;


    case "/mydir/index.php/login":
        $model = new Model();
        if ($model->isError()) {
            die($model->getError());
        }

        if (!isset($_SESSION["user"])) {
            if (isset($_POST["login_ok"])) {
                $username = $_POST["username"];
                $salt = $model->getUserSalt($username);
                $concat = $salt . $_POST["pword"] . PEPPER;
                $hash = md5($concat);
                $table_hash = $model->getUserPWord($username);

                echo "<!DOCTYPE html>
                      <html>
                          <body onload='document.getElementById(\"pword_form\").submit()'>
                              <form method='post' id='pword_form' name='pword_form' action='login'>
                                  <input type='hidden' name='table_hash' id='table_hash' value=$table_hash>
                                  <input type='hidden' name='username' id='username' value=$username>
                                  <input type='hidden' name='password' id='password' value=$hash>
                              </form>
                          </body>
                      </html>
                    ";
            } else {
                require 'login.php';
            }
        } else {
            $user = $_SESSION["user"];
            header('location: /mydir/index.php/task');
        }
        break;


    case "/mydir/index.php/register":
        $model = new Model();
        if ($model->isError()) {
            die($model->getError());
        }
        require "register.php";
        break;


    case "/mydir/index.php/change_preferences":
        $model = new Model();
        if ($model->isError()) {
            die($model->getError());
        }
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            require "change_pref.php";
        } else {
            header('location: /mydir/index.php/login');
        }
        break;


    case "/mydir/index.php/change_colourmode":
        $model = new Model();
        if ($model->isError()) {
            die($model->getError());
        }
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            require "change_colourmode.php";
        } else {
            header('location: /mydir/index.php/login');
        }
        break;


    case "/mydir/index.php/task":
        $model = new Model();
        if ($model->isError()) {
            die($model->getError());
        }

        if (isset($_SESSION['user'])) {
            $user = $_SESSION["user"];
            require "task.php";
        } else {
            header('location: /mydir/index.php/login');
        }
        break;

    case "/mydir/index.php/logout":
        $model = new Model();
        if ($model->isError()) {
            die($model->getError());
        }
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
        header("location: /mydir/index.php/login");
        break;

    case "/mydir/index.php/add_task":
        $model = new Model();
        if ($model->isError()) {
            die($model->getError());
        }

        if (isset($_SESSION['user'])) {
            if (isset($_POST["button_ok"])) {
                $user = $_SESSION["user"];
                $user_id = $_SESSION["user_id"];
                $task_name = $_POST["task_name"];
                $task_description = $_POST["description"];
                $due_date = $_POST["duedate"];
                $due_time = $_POST["duetime"];
                $state = $_POST["state"];
                $is_done = false;

                if ($state == "done") {
                    $is_done = true;
                } elseif ($state == "not_done") {
                    $is_done = false;
                }

                $model->insertNewTask($task_name, $task_description, $due_date, $due_time, $user, $is_done, $user_id);

                if ($model->isError()) {
                    $task_added = false;
                } else {
                    $task_added = true;
                }

                echo "<html>
                    <body onload='document.getElementById(\"added_form\").submit()'>
                      <form method='post' id='added_form' name='added_form' action='add_task'>
                        <input type='hidden' name='table_hash' id='table_hash' value=$task_added>
                      </form>
                    </body>
                  </html>
                ";

                header("location: /mydir/index.php/task");

            } else {
                $user = $_SESSION['user'];
                require "add_task.php";
            }
        } else {
            header('location: /mydir/index.php/login');
        }
        break;

    case "/mydir/index.php/import":
        require "import.php";
        break;

    case "/mydir/index.php/import_action":
        if (isset($_POST["import_selections"])) {
            require "import_action.php";
        } else {
            header("location: /mydir/index.php/task");
        }
        break;

    case "/mydir/index.php/edit":
        $model = new Model();
        if ($model->isError()) {
            die($model->getError());
        }

        if (isset($_SESSION['user'])) {
            if (isset($_POST["button_ok"])) {
                $user = $_SESSION["user"];
                $user_id = $_SESSION["user_id"];

                $task_name = $_POST["task_name"];

                $task_description = $_POST["description"];

                $due_date = $_POST["duedate"];

                $due_time = $_POST["duetime"];

                $state = $_POST["state"];

                $task_id = $_POST["task_identifier"];

                $is_done = false;

                $web_id = $model->getWebId($task_id);

                if ($state == "done") {
                    $is_done = true;
                } elseif ($state == "not_done") {
                    $is_done = false;
                }

                $model->editTask($task_name, $task_description, $due_date, $due_time, $user, $is_done, $task_id);

                if ($model->isError()) {
                    $task_edited = false;
                } else {
                    $task_edited = true;
                }

                header("location: /mydir/index.php/task");

            } elseif (isset($_POST["edit"])) {
                date_default_timezone_set('Europe/London');

                $user = $_SESSION['user'];
                $task_id = $_POST["task_id"];
                $task_name = $model->getNameByID($task_id);
                $desc = $model->getDescByID($task_id);
                $task_desc = urlencode($desc[0]);
                $due_date_time = $model->getDueDateByID($task_id);
                $is_done = $model->getStateByID($task_id);

                try {
                    $date_time = new DateTime($due_date_time);
                } catch (Exception $e) {
                    echo "DateTime error:" . $e;
                }

                $due_date = $date_time->format('Y-m-d');
                $due_time = $date_time->format('H:i:s');

                echo "<html>
                    <body onload='document.getElementById(\"edit_form\").submit()'>
                      <form method='post' id='edit_form' name='edit_form' action='edit'>
                        <input type='hidden' name='task_name' id='task_name' value=$task_name>
                        <input type='hidden' name='task_desc' id='task_desc' value=$task_desc>
                        <input type='hidden' name='is_done' id='is_done' value=$is_done>
                        <input type='hidden' name='due_date' id='due_date' value=$due_date>
                        <input type='hidden' name='due_time' id='due_time' value=$due_time>
                        <input type='hidden' name='task_identifier' id='task_identifier' value=$task_id>
                      </form>
                    </body>
                  </html>
                ";
            } else {
                require "edit.php";
            }
        } else {
            header('location: /mydir/index.php/login');
        }
        break;

    case "/mydir/index.php/delete":
        $model = new Model();
        if ($model->isError()) {
            die($model->getError());
        }

        if (isset($_POST["delete_confirm"])){
            $model->deleteTask($_POST["delete_confirm"]);
            header("location: /mydir/index.php/task");
        } else {
            $task_id = $_POST["task_id"];
            echo "<html>
                        <body onload='document.getElementById(\"delete_form\").submit()'>
                          <form method=\"post\" id=\"edit_form\" name=\"delete_form\" action=\"delete\">
                            <input type=\"hidden\" name=\"task_id\" id=\"task_id\" value=$task_id>
                          </form>
                        </body>
                      </html>
                    ";
            require "delete.php";
        }

        break;

    case "/mydir/index.php/export":
        require "export.php";

        break;

    case "/mydir/index.php/check_uncheck":
        require "check_uncheck.php";

        break;

    case "/mydir/index.php/check_action":
        require "check_action.php";

        break;

    default:
        header("HTTP/1.1 404 Not Found");
        echo "<html lang='en'>
                <head><title>Page Not Found</title></head>
                <body>
                <h1>404 - Page Not Found</h1>
                <h2>The Page: $url has not been found.</h2>
                <a style='font-size: 24px' href='/mydir/index.php/login'>Try logging in.</a>
                </body>
              </html>";
}