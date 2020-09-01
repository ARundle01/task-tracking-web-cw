<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Add Tasks</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php include "pref_check.php" ?>
    </head>
    <body>
        <div class="row">
            <div class="col-1">
                <h2>Add Task</h2>
            </div>
        </div>

        <form name="add_task_form" method="post" action="/mydir/index.php/add_task">
            <div class="row">
                <div class="col-1 mobile_border">
                    <label for="task_name"><h3>Task Name: </h3></label><input type="text" id="task_name" name="task_name" required>
                </div>
            </div>
            <div class="row">
                <div class="col-12 mobile_border">
                    <h3>Description:</h3>
                    <textarea name="description" rows="3" maxlength="255" id="description"></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-1 mobile_border">
                    <h3>Due Date:</h3>
                    <input type="date" id="duedate" name="duedate" required>
                </div>
                <div class="col-1 mobile_border">
                    <h3>Time Due:</h3>
                    <input type="time" id="duetime" name="duetime" step="1" required>
                </div>
            </div>
            <div class="row">
                <div class="col-2 mobile_border">
                    <h3>State:</h3>
                    <label for="state_done">Done: </label><input type="radio" id="state_done" name="state" value="done" required>
                    <label for="state_not_done">Not Done:</label><input type="radio" id="state_not_done" name="state" value="not_done" required>
                </div>
            </div>
            <div class="row">
                <div class="col-1 no_bottom">
                    <input type="submit" name="button_ok" id="button_ok" value="Ok">
                </div>
            </div>
        </form>
        <form action="/mydir/index.php/task">
            <div class="col-1 no_top">
                <input type="submit" value="Cancel">
            </div>
        </form>

        <?php
            if (isset($_POST["task_added"])) {
                if ($_POST["task_added"] === true) {
                    echo "Task added successfully.";
                } else {
                    echo "Task could not be created.";
                }
            }
        ?>

    </body>
</html>
