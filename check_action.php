<?php
    session_start();
    require "config/api_key.php";

    $model = new Model();
    if ($model->isError()) {
        die($model->getError());
    }

    $task_id = $_POST["task_id"];
    $web_id = $model->getWebId($task_id);

    $user_id = $_SESSION["user_id"];

    if (isset($_POST["check_ok"])) {
        $state = "check";
    } elseif (isset($_POST["uncheck_ok"])) {
        $state = "uncheck";
    }

    $ch = curl_init();

    if ($state == "check") {
        $xml_id = $user_id + API_KEY;
        $user_xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><user><id>" . $xml_id . "</id></user>";
        $url = "http://example.ac.uk/userdir/check.php/" . $web_id;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, "Content-Type: text/xml");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $user_xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        echo "<html><script>console.log(" . $response . ")</script></html>";
        curl_close($ch);

        if ($response == 409) {
            $model->updateState($task_id, 1);
            echo "<!DOCTYPE html><html><body><script>alert(\"Task is already marked as done.\"); window.location.replace(\"http://example.ac.uk/mydir/index.php/check_uncheck\")</script></body></html>";
        }

        if ($response == 200) {
            $model->updateState($task_id, 1);
            echo "<!DOCTYPE html><html><body><script>alert(\"Task successfully checked as done.\"); window.location.replace(\"http://example.ac.uk/mydir/index.php/check_uncheck\")</script></body></html>";
        }

        if ($response == 404) {
            $model->updateWebId(0, $task_id);
            echo "<!DOCTYPE html><html><body><script>alert(\"Task does not exist.\"); window.location.replace(\"http://example.ac.uk/mydir/index.php/check_uncheck\")</script></body></html>";
        }

        if ($response == 400) {
            echo "<!DOCTYPE html><html><body><script>alert(\"Bad Request: User or Task ID is not valid.\"); window.location.replace(\"http://example.ac.uk/mydir/index.php/check_uncheck\")</script></body></html>";
        }
    }

    if ($state == "uncheck") {
        $xml_id = $user_id + API_KEY;
        $user_xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><user><id>" . $xml_id . "</id></user>";
        $url = "http://example.ac.uk/userdir/uncheck.php/" . $web_id;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, "Content-Type: text/xml");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $user_xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        echo "<html><script>console.log(" . $response . ")</script></html>";
        curl_close($ch);

        if ($response == 401) {
            $model->deleteTask($task_id);
            echo "<!DOCTYPE html><html><body><script>alert(\"Task was marked as done by someone else.\"); window.location.replace(\"http://example.ac.uk/mydir/index.php/check_uncheck\")</script></body></html>";
        }

        if ($response == 200) {
            $model->updateState($task_id, 0);
            echo "<!DOCTYPE html><html><body><script>alert(\"Task successfully unchecked as done.\"); window.location.replace(\"http://example.ac.uk/mydir/index.php/check_uncheck\")</script></body></html>";
        }

        if ($response == 404) {
            $model->updateWebId(0, $task_id);
            echo "<!DOCTYPE html><html><body><script>alert(\"Task does not exist.\"); window.location.replace(\"http://example.ac.uk/mydir/index.php/check_uncheck\")</script></body></html>";
        }

        if ($response == 400) {
            echo "<!DOCTYPE html><html><body><script>alert(\"Bad Request: User or Task ID is not valid.\"); window.location.replace(\"http://example.ac.uk/mydir/index.php/check_uncheck\")</script></body></html>";
        }

        if ($response == 409) {
            echo "<!DOCTYPE html><html><body><script>alert(\"Task has already been unchecked as done.\"); window.location.replace(\"http://example.ac.uk/mydir/index.php/check_uncheck\")</script></body></html>";
        }
    }