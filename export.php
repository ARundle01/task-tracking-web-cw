<?php
    session_start();
    ?>

<!DOCTYPE html>
<html>
    <head>
        <title>Exporting...</title>
    </head>
        <?php
            $model = new Model();
            if ($model->isError()) {
                die($model->getError());
            }
            $user_id = $_SESSION["user_id"];
            $task_id = $_POST["task_identifier"];

            $task_name = $model->getNameByID($task_id);
            $task_description = $model->getDescByID($task_id);
            $task_description = $task_description[0];
            $task_due = $model->getDueDateByID($task_id);

            $name_xml = "<name>" . $task_name . "</name>";
            $desc_xml = "<description>" . $task_description . "</description>";
            $due_xml = "<due>" . $task_due . "</due>";

            $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><taskinfo>" . $name_xml . $due_xml . $desc_xml . "</taskinfo> ";

            $ch = curl_init();
            $headers = array('Content-Type: text/xml');

            curl_setopt($ch, CURLOPT_URL, "http://example.ac.uk/userdir/add.php");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);

            $result = curl_exec($ch);
            $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            $taskid_xml = simplexml_load_string($result);
            $web_id = $taskid_xml->id;

            if ($response == 200) {
                $model->updateWebId($web_id, $task_id);
                header("location: /mydir/index.php/task");
            }
        ?>
</html>
