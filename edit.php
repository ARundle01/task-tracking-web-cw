<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Edit Tasks</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php include "pref_check.php" ?>
        <script>
            function submitExport() {
                if (confirm("Do you want to export this task?")) {
                    document.getElementById("export_submission").submit();
                }
            }
        </script>
    </head>
    <body>
        <div class="row">
            <div class="col-1">
                <h2>Edit Task</h2>
            </div>
        </div>

        <?php
        $task_id = $_POST["task_identifier"];

        $task_name = $_POST["task_name"];

        $task_desc = $_POST["task_desc"];
        $task_desc = urldecode($task_desc);

        $due_date = $_POST["due_date"];

        $due_time = $_POST["due_time"];

        $is_done = $_POST["is_done"];

        ?>

        <form name="add_task_form" method="post" action="/mydir/index.php/edit">
            <div class="row">
                <div class="col-1 mobile_border">
                    <label for="task_name"><h3>Task Name: </h3></label><input type="text" id="task_name" name="task_name" value="<?php echo $task_name; ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-12 mobile_border">
                    <h3>Description:</h3>
                    <textarea name="description" rows="3" maxlength="255" id="description"><?php echo $task_desc; ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-1 mobile_border">
                    <h3>Due Date:</h3>
                    <input type="date" id="duedate" name="duedate" value="<?php echo $due_date; ?>" required>
                </div>
                <div class="col-1 mobile_border">
                    <h3>Time Due:</h3>
                    <input type="time" id="duetime" name="duetime" step="1" value="<?php echo $due_time; ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-2 mobile_border">
                    <h3>State:</h3>
                    <?php
                        if ($is_done == 1) {
                            echo "
                    <label for=\"state_done\">Done: </label><input type=\"radio\" id=\"state_done\" name=\"state\" value=\"done\" checked required>
                    <label for=\"state_not_done\">Not Done:</label><input type=\"radio\" id=\"state_not_done\" name=\"state\" value=\"not_done\" required>
                    ";
                        } elseif ($is_done == 0) {
                            echo "
                    <label for=\"state_done\">Done: </label><input type=\"radio\" id=\"state_done\" name=\"state\" value=\"done\" required>
                    <label for=\"state_not_done\">Not Done:</label><input type=\"radio\" id=\"state_not_done\" name=\"state\" value=\"not_done\" checked required>
                    ";
                        }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-1 no_bottom">
                    <input type="hidden" name="task_identifier" id="task_identifier" value="<?php echo $task_id; ?>">
                    <input type="submit" name="button_ok" id="button_ok" value="Ok">
                </div>
            </div>
        </form>
        <form action="/mydir/index.php/task">
            <div class="col-1 no_top">
                <input type="submit" value="Cancel">
            </div>
        </form>
        <div class="row">
            <button onclick="submitExport()">Export</button>

            <form action="/mydir/index.php/export" method="post" id="export_submission">
                <input type="hidden" name="task_identifier" id="task_identifier" value="<?php echo $task_id; ?>">
            </form>
        </div>
    </body>
</html>

