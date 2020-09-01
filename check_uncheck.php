<?php
    session_start();
    ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Check/Uncheck</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php include "pref_check.php" ?>
    </head>
    <body>
        <h1>Check or Uncheck Tasks</h1>
        <div class="row">
            <form action="/mydir/index.php/task" class="col-1 with_border">
                <input class="back_button" type="submit" value="Go Back">
            </form>
        </div>
        <br>
        <div class="row">
            <div class="col-1 with_border is_done">
                <p>Is Done?</p>
            </div>
            <div class="col-4 with_border task_box">
                <p class="task_name">Task Name</p>
                <p class="task_desc">Task Description</p>
            </div>
        </div>
        <?php
            $model = new Model();
            if ($model->isError()) {
                die($model->getError());
            }

            $user = $_SESSION["user"];
            $user_id = $_SESSION["user_id"];

            $task_names = $model->getWebNames($user);
            $task_descs = $model->getWebDesc($user);
            $task_duedates = $model->getWebDueDate($user);
            $task_ids = $model->getWebTaskID($user);
            $task_states = $model->getWebStates($user);

            $length = sizeof($task_names);

            for ($row = 0; $row < $length; $row++) {
                $current_name = $task_names[$row];
                $current_desc = $task_descs[$row];
                $current_date = $task_duedates[$row];
                $current_id = $task_ids[$row];
                $current_state = $task_states[$row];

                if ($current_state === 1) {
                    $current_state = "Yes";
                } else {
                    $current_state = "No";
                }

                echo "
            <div class=\"row\">
                <div class=\"col-1 with_border state_box\">
                    <p class=\"task_state\">$current_state</p>
                </div>
                <div class=\"col-4 with_border task_box\">
                    <p class=\"task_name\">$current_name</p>
                    <p class=\"task_desc\">$current_desc</p>
                </div>
                <div class=\"col-1 with_border edit_button\">
                    <form action=\"/mydir/index.php/check_action\" method=\"post\">
                        <input class=\"check_button\" type=\"submit\" id=\"check_ok\" name=\"check_ok\" value=\"Check\" style=\"align-content: center\">
                        <input type=\"hidden\" id=\"task_id\" name=\"task_id\" value=\"$current_id\">
                    </form>
                </div>
                <div class=\"col-1 with_border delete_button\">
                    <form method=\"post\" action=\"/mydir/index.php/check_action\">
                        <input class=\"check_button\" type=\"submit\" name=\"uncheck_ok\" id=\"uncheck_ok\" value=\"Uncheck\" style=\"align-content: center\">
                        <input type=\"hidden\" id=\"task_id\" name=\"task_id\" value=\"$current_id\">         
                    </form>
                </div>
            </div>
            ";
            }
        ?>
    </body>
</html>
