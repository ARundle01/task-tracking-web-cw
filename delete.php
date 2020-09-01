<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete a task</title>
    <?php include "pref_check.php" ?>
</head>
<body>
    <div class="row">
        <div class="col-12"><h1>Delete a Task</h1></div>
    </div>
    <div class="row">
        <div class="col-1 button">
            <form action="/mydir/index.php/delete" method="post">
                <input type="hidden" name="delete_confirm" value="<?php echo $_POST['task_id']; ?>">
                <input type="submit" name="delete_ok" value="Delete">
            </form>
        </div>
        <div class="col-1 button">
            <form action="task">
                <input type="submit" value="Cancel">
            </form>
        </div>
    </div>
</body>
</html>