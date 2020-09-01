<?php
    session_start();
    ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tasks</title>
        <?php include "pref_check.php" ?>
    </head>
    <body>
        <div class="row">
            <div class="col-1">
                <h1 style="margin-top: 0; margin-bottom: 0;">Tasks</h1>
            </div>
            <div class="col-7"></div>
            <div class="col-2">
                <h3 style="margin-top: 0; margin-bottom: 0;"><?php echo "Logged in as\n" . $_SESSION['user'];?></h3>
            </div>
            <div class="col-1 button">
                <form action="/mydir/index.php/change_preferences">
                    <input type="submit" value="Change Preferences" style="white-space: pre-wrap">
                </form>
            </div>
            <div class="col-1 button">
                <form action="/mydir/index.php/logout">
                    <input type="submit" value="Logout">
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-2 with_border button">
                <form action="/mydir/index.php/add_task" style="padding: 14px">
                    <input type="submit" value="Add">
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-2 with_border button">
                <form action="/mydir/index.php/import" style="padding: 14px">
                    <input type="submit" value="Import">
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-2 with_border button">
                <form action="/mydir/index.php/check_uncheck" style="padding: 14px">
                    <input type="submit" value="Check/Uncheck Tasks">
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-1 with_border task_select_title">
                <p>Select a Task</p>
            </div>
            <div class="col-5 with_border name_title">
                <p>Name/Date</p>
            </div>
        </div>
        <?php
            $user = $_SESSION["user"];
            $task_names = $model->getTaskName($user);
            $task_desc = $model->getTaskDesc($user);
            $task_ids = $model->getTaskID($user);
            $task_dates = $model->getTaskDueDate($user);

            $length = sizeof($task_ids);

            for ($row = 0; $row < $length; $row++) {
                $current_name = $task_names[$row];
                $current_desc = $task_desc[$row];
                $current_id = $task_ids[$row];

                echo "
            <div class=\"row\">
                <div class=\"col-1 with_border select_box\">
                    <input class=\"select_task\" id=\"select\" type=\"checkbox\" value=\"Select\">
                    <p style=\"margin-bottom: 0; margin-top: 0\">Select</p>
                </div>
                <div class=\"col-5 with_border task_box\">
                    <p class=\"task_name\">$current_name</p>
                    <p class=\"task_desc\">$current_desc</p>
                </div>
                <div class=\"col-1 with_border edit_button\">
                    <form action=\"/mydir/index.php/edit\" method=\"post\">
                        <input class=\"button\" type=\"submit\" id=\"edit\" name=\"edit\" value=\"Edit\" style=\"align-content: center\">
                        <input type=\"hidden\" id=\"task_id\" name=\"task_id\" value=\"$current_id\">
                    </form>
                </div>
                <div class=\"col-1 with_border delete_button\">
                    <form method=\"post\" action=\"/mydir/index.php/delete\">
                        <input class=\"button\" type=\"submit\" id=\"delete\" value=\"Delete\" style=\"align-content: center\">
                        <input type=\"hidden\" id=\"task_id\" name=\"task_id\" value=\"$current_id\">         
                    </form>
                </div>
            </div>
            ";
            }
        ?>
    </body>
</html>