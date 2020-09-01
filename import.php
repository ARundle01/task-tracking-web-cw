<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Import Tasks</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php include "pref_check.php" ?>

        <script>
            function loadTaskList() {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState === 4 && this.status === 200) {
                        showTaskList(this);
                    }
                };
                xhttp.open("GET", "http://example.ac.uk/userdir/tasks.php", true);
                xhttp.send();
            }

            var name_array = [], date_array = [], id_array = [];

            function showTaskList(xml) {
                var task, row, xmlDoc, name_text, date_text, id_text;

                xmlDoc = xml.responseText;

                var parser = new DOMParser();

                xmlDoc = parser.parseFromString(xmlDoc, "text/xml");

                task = xmlDoc.getElementsByTagName("name");
                var date = xmlDoc.getElementsByTagName("due");
                var id = xmlDoc.getElementsByTagName("id");
                name_text = "";
                date_text = "";
                id_text = "";
                if (task.length === 0) {
                    document.getElementById("name").innerHTML = "No tasks";
                } else {
                    for (row = 0; row < task.length; row++) {

                        if (typeof task[row].childNodes[0] == "undefined") {
                            name_text += "Name not given" + "<br>";
                        } else {
                            name_text += task[row].childNodes[0].nodeValue + "<br>";
                        }

                        if (typeof date[row].childNodes[0] == "undefined") {
                            date_text += "Date not given" + "<br>";
                        } else {
                            date_text += date[row].childNodes[0].nodeValue + "<br>";
                        }

                        if (typeof id[row].childNodes[0] == "undefined") {
                            id_text += "ID not given" + "<br>";
                        } else {
                            id_text += id[row].childNodes[0].nodeValue + "<br>";
                            id_array.push(id[row].childNodes[0].nodeValue);
                        }

                    }
                    document.getElementById("name").innerHTML = name_text;
                    document.getElementById("date").innerHTML = date_text;
                    document.getElementById("id").innerHTML = id_text;

                    var entry;
                    for (entry = 0; entry < id_array.length; entry++) {
                        createCheckbox(id_array[entry])
                    }
                }
            }

            function createCheckbox(id) {
                var element = document.createElement("input");
                element.style.marginTop = "1px";
                element.style.marginBottom = "1px";
                element.type = "checkbox";
                // element.className = "checkbox_content";
                element.value = id;
                element.name = "import_selections[]";

                var breaker = document.createElement("BR");
                var div = document.getElementById("checkboxes");
                div.appendChild(element);
                div.appendChild(breaker);
            }

            function submitImports() {
                if (confirm("Are you sure you want to import these tasks?")) {
                    document.getElementById("import_action").submit()
                }
            }

        </script>
    </head>
    <body onload="loadTaskList()">
        <div class="row">
            <h1>Import Tasks</h1>
        </div>
        <div class="row">
            <div class="name_header col-1">Name:</div>
            <div class="date_header col-1">Date:</div>
            <div class="id_header col-1">ID:</div>
        </div>
        <div class="row">
            <div id="name" class="name_content col-1"></div>
            <div id="date" class="date_content col-1"></div>
            <div id="id" class="id_content col-1"></div>
            <form method="post" id="import_action" name="import_action" action="/mydir/index.php/import_action">
                <div id="checkboxes" class="col-1 checkbox_content"></div>
            </form>
        </div>
        <div class="row">
            <div class="col-1">
                <input type="submit" value="Import" onclick="submitImports()">
            </div>
            <div class="col-1">
                <form action="/mydir/index.php/task">
                    <input type="submit" value="Cancel">
                </form>
            </div>
        </div>
    </body>
</html>
