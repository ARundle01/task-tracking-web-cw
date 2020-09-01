<?php
    session_start();
    ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Importing...</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php include "pref_check.php"?>
    </head>

    <body>
        <?php
            $model = new Model();
            if ($model->isError()) {
                die($model->getError());
            }

            $id_array = $_POST["import_selections"];
            $user = $_SESSION["user"];

            $results_array = [];
            $object_array = [];

            $ch = curl_init();
            $headers = array('Content-Type: text/html');

            foreach ($id_array as $id) {
                curl_setopt($ch, CURLOPT_URL, "http://example.ac.uk/userdir/task.php/" . $id);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_HEADER, 0);

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $result = curl_exec($ch);
                array_push($object_array, simplexml_load_string($result));
            }

            foreach ($object_array as $xml) {
                $id = $xml->id;
                $name = $xml->name;
                $description = $xml->description;
                date_default_timezone_set('Europe/London');

                try {
                    $due = new DateTime($xml->due);
                } catch (Exception $e) {
                    echo "DateTime Error:" . $e;
                }

                $date = $due->format('Y-m-d');
                $time = $due->format('H:i:s');

                $user_id = $_SESSION["user_id"];

                $user_xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><user><id>" . $user_id . "</id></user>";

                curl_setopt($ch, CURLOPT_URL, "http://example.ac.uk/userdir/check.php/" . $id);
                curl_setopt($ch, CURLOPT_HTTPHEADER, "Content-Type: text/xml");
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $user_xml);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $result = curl_exec($ch);
                $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                if ($response == 409) {
                    $state = true;
                } elseif ($response == 200) {
                    curl_setopt($ch, CURLOPT_URL, "http://example.ac.uk/userdir/uncheck.php/" . $id);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, "Content-Type: text/xml");
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $user_xml);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    $result = curl_exec($ch);
                    $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                    if ($response == 200) {
                        $state = false;
                    }
                }
                $model->insertNewTask($name, $description, $date, $time, $user, $state, $user_id, $id);

                if ($model->isError()) {echo "Error:" . $model->getError();}
            }

            header("location: /mydir/index.php/task");
        ?>
    </body>
</html>
