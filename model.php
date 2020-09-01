<?php
require "config/connection.php";
require "config/pepper.php";

class Model
{
    private $connection;
    private $pepper;
    private $error = false;

    public function isError()
    {
        return $this->error !== false;
    }

    public function getError()
    {
        return $this->error;
    }

    public function __construct()
    {
        $this->connection = new mysqli(DB_SERVER_NAME, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);
        $this->pepper = PEPPER;

        if ($this->connection->connect_error) { // Try to connect, send error message if it cannot
            die("Connection Error: " . $this->connection->connect_error);
        }
    }

    public function __destruct()
    {
        $this->connection->close();
    }

    function getUserSalt($username)
    {
        $sql = "SELECT salt FROM Users WHERE username = ?;";

        if ($stmt = $this->connection->prepare($sql)) {
            $stmt->bind_param('s', $username);

            $stmt->execute();

            $stmt->store_result();

            $stmt->bind_result($salt);

            $stmt->fetch();

            $stmt->free_result();

            $stmt->close();

            return $salt;
        }
        $this->error = "MySql Error: user does not exist";
    }

    function getUserPWord($username)
    {
        $sql = "SELECT password FROM Users WHERE username = ?;"; // Select pwords for specific username

        if ($stmt = $this->connection->prepare($sql)) {
            $stmt->bind_param('s', $username);

            $stmt->execute();

            $stmt->store_result();

            $stmt->bind_result($table_hash);

            $stmt->fetch();

            $stmt->free_result();

            $stmt->close();

            return $table_hash;
        }
        $this->error = "MySql Error: user does not exist";
    }

    function insertNewUser($username, $hash, $salt)
    {
        $sql = "SELECT id FROM Users ORDER BY id DESC LIMIT 1;";
        if ($stmt = $this->connection->prepare($sql)) {
            $stmt->execute();

            $stmt->store_result();
            $stmt->bind_result($id);
            $stmt->fetch();

            $stmt->free_result();
            $stmt->close();

            $new_id = $id + 1;
        } else {
            $new_id = 0;
        }

        $sql = "INSERT INTO Users (username, password, salt, id) VALUES ('$username', '$hash', '$salt', '$new_id');";

        if ($this->connection->query($sql) === false) {
            if ($this->connection->errno == 1062) {
                $this->error = "Mysql error: Username already exists";
            } else {
                $this->error = "Mysql error: " . $this->connection->error;
            }
        }
        echo "New User created successfully \n";
    }

    function getUserId($username)
    {
        $sql = "SELECT id FROM Users WHERE username = ?;";

        if ($stmt = $this->connection->prepare($sql)) {
            $stmt->execute();

            $stmt->bind_param("s", $username);

            $stmt->execute();
            $stmt->store_result();

            $stmt->bind_result($user_id);
            $stmt->fetch();

            $stmt->free_result();;
            $stmt->close();

            return $user_id;
        } else {
            $this->error = "MySQL Error: User does not exist";
        }
    }

    function insertNewTask($task_name, $task_description, $due_date, $time_due, $user, $is_done, $user_id, $web_id = 0)
    {
        date_default_timezone_set('Europe/London');

        try {
            $date = new DateTime($due_date);
            $time = new DateTime($time_due);
        } catch (Exception $e) {echo "Datetime Error: " . $e;}

        /** @var DateTime $date
         * Due date for task in format Year-month-day.
         */

        /** @var DateTime $time
         * Due time for task in format Hour-min-sec.
         */
        $date_formatted = $date->format('Y-m-d');
        $time_formatted = $time->format('H:i:s');

        $datetime = $date_formatted . "\n" . $time_formatted;

        $sql = "INSERT INTO Tasks (task_name, task_description, due_date, username, is_done, web_id, user_id) VALUES ('$task_name', '$task_description', '$datetime', '$user', '$is_done', '$web_id', '$user_id');";

        if ($this->connection->query($sql) === false) {
            $this->error = "Mysql error: " . $this->connection->error;
        } else {
            echo "Task created successfully.";
        }
    }

    function editTask($task_name, $task_description, $due_date, $time_due, $user, $is_done, $id)
    {
        date_default_timezone_set('Europe/London');

        try {
            $date = new DateTime($due_date);
            $time = new DateTime($time_due);
        } catch (Exception $e) {echo "Datetime Error: " . $e;}

        /** @var DateTime $date
         * Due date for task in format Year-month-day.
         */

        /** @var DateTime $time
         * Due time for task in format Hour-min-sec.
         */

        $date_formatted = $date->format('Y-m-d');
        $time_formatted = $time->format('H:i:s');

        $datetime = $date_formatted . "\n" . $time_formatted;

        $sql = "UPDATE Tasks SET task_name='$task_name', task_description='$task_description', due_date='$datetime', username='$user', is_done='$is_done' WHERE identifier='$id';";

        if ($this->connection->query($sql) === false) {
            $this->error = "Mysql error: " . $this->connection->error;
        } else {
            echo "Task edited successfully.";
        }
    }

    function deleteTask($id)
    {
        $sql = "DELETE FROM Tasks WHERE identifier='$id';";

        if ($this->connection->query($sql) === false) {
            $this->error = "Mysql error: " . $this->connection->error;
        } else {
            echo "Task deleted successfully.";
        }
    }

    function getTaskName($username)
    {
        $sql = "SELECT (task_name) FROM Tasks WHERE username = ?;";

        if ($stmt = $this->connection->prepare($sql)) {
            $stmt->bind_param('s', $username);

            $stmt->execute();

            $stmt->store_result();
            if ($stmt->num_rows !== 0) {
                $stmt->bind_result($name_row);
                while ($stmt->fetch()) {
                    $names[] = $name_row;
                }

                $stmt->free_result();

                $stmt->close();

                /** @var array $names */
                return $names;

            } else {

                $stmt->free_result();

                $stmt->close();
                $this->error = "MySql Error: no rows in table";
            }
        }
        $this->error = "MySql Error: user does not exist";
    }

    function getTaskDesc($username)
    {
        $sql = "SELECT (task_description) FROM Tasks WHERE username = ?;";

        if ($stmt = $this->connection->prepare($sql)) {
            $stmt->bind_param('s', $username);

            $stmt->execute();

            $stmt->store_result();
            if ($stmt->num_rows !== 0) {
                $stmt->bind_result($desc_row);
                while ($stmt->fetch()) {
                    $desc[] = $desc_row;
                }

                $stmt->free_result();

                $stmt->close();

                /** @var array $desc */
                return $desc;
            } else {

                $stmt->free_result();

                $stmt->close();
                $this->error = "MySql Error: no rows in table";
            }
        }
        $this->error = "MySql Error: user does not exist";
    }

    function getTaskID($username)
    {
        $sql = "SELECT (identifier) FROM Tasks WHERE username = ?;";

        if ($stmt = $this->connection->prepare($sql)) {
            $stmt->bind_param('s', $username);

            $stmt->execute();

            $stmt->store_result();
            if ($stmt->num_rows !== 0) {
                $stmt->bind_result($id_row);
                while ($stmt->fetch()) {
                    $ids[] = $id_row;
                }

                $stmt->free_result();

                $stmt->close();

                /** @var array $ids */
                return $ids;
            } else {

                $stmt->free_result();

                $stmt->close();
                $this->error = "MySql Error: no rows in table";
            }
        }
        $this->error = "MySql Error: user does not exist";
    }

    function getTaskDueDate($username)
    {
        $sql = "SELECT (due_date) FROM Tasks WHERE username = ?;";

        if ($stmt = $this->connection->prepare($sql)) {
            $stmt->bind_param('s', $username);

            $stmt->execute();

            $stmt->store_result();
            if ($stmt->num_rows !== 0) {
                $stmt->bind_result($duedate_row);
                while ($stmt->fetch()) {
                    $duedates[] = $duedate_row;
                }

                $stmt->free_result();

                $stmt->close();

                /** @var array $duedates */
                return $duedates;
            } else {

                $stmt->free_result();

                $stmt->close();
                $this->error = "MySql Error: no rows in table";
            }
        }
        $this->error = "MySql Error: user does not exist";
    }

    function getDueDateByID($id)
    {
        $sql = "SELECT (due_date) FROM Tasks WHERE identifier = ?;";

        if ($stmt = $this->connection->prepare($sql)) {
            $stmt->bind_param('s', $id);

            $stmt->execute();

            $stmt->store_result();
            if ($stmt->num_rows !== 0) {
                $stmt->bind_result($duedate);
                $stmt->fetch();

                $stmt->free_result();

                $stmt->close();

                return $duedate;
            } else {

                $stmt->free_result();

                $stmt->close();
                $this->error = "MySql Error: no rows in table";
            }
        }
        $this->error = "MySql Error: user does not exist";
    }

    function getNameByID($id)
    {
        $sql = "SELECT (task_name) FROM Tasks WHERE identifier = ?;";

        if ($stmt = $this->connection->prepare($sql)) {
            $stmt->bind_param('s', $id);

            $stmt->execute();

            $stmt->store_result();
            if ($stmt->num_rows !== 0) {
                $stmt->bind_result($task_name);
                $stmt->fetch();

                $stmt->free_result();

                $stmt->close();

                return $task_name;
            } else {

                $stmt->free_result();

                $stmt->close();
                $this->error = "MySql Error: no rows in table";
            }
        }
        $this->error = "MySql Error: user does not exist";
    }

    function getDescByID($id)
    {
        $sql = "SELECT (task_description) FROM Tasks WHERE identifier = ?;";

        if ($stmt = $this->connection->prepare($sql)) {
            $stmt->bind_param('s', $id);

            $stmt->execute();

            $stmt->store_result();
            if ($stmt->num_rows !== 0) {
                $stmt->bind_result($desc_row);
                while ($stmt->fetch()) {
                    $desc[] = $desc_row;
                }

                $stmt->free_result();

                $stmt->close();

                /** @var array $desc */
                return $desc;
            } else {

                $stmt->free_result();

                $stmt->close();
                $this->error = "MySql Error: no rows in table";
            }
        }
        $this->error = "MySql Error: user does not exist";
    }

    function getStateByID($id)
    {
        $sql = "SELECT (is_done) FROM Tasks WHERE identifier = ?;";

        if ($stmt = $this->connection->prepare($sql)) {
            $stmt->bind_param('s', $id);

            $stmt->execute();

            $stmt->store_result();
            if ($stmt->num_rows !== 0) {
                $stmt->bind_result($is_done);
                $stmt->fetch();

                $stmt->free_result();

                $stmt->close();

                /** @var int $is_done */
                return $is_done;
            } else {

                $stmt->free_result();

                $stmt->close();
                $this->error = "MySql Error: no rows in table";
            }
        }
        $this->error = "MySql Error: user does not exist";
    }

    function updateWebId($id, $task_id)
    {
        $sql = "UPDATE Tasks SET web_id='$id' WHERE identifier = '$task_id';";

        if ($this->connection->query($sql) === false) {
            $this->error = "Mysql error: " . $this->connection->error;
        }
    }

    function getWebId($id)
    {
        $sql = "SELECT web_id FROM Tasks WHERE identifier = ?;";

        if ($stmt = $this->connection->prepare($sql)) {
            $stmt->bind_param("i", $id);

            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows !== 0) {
                $stmt->bind_result($web_id);
                $stmt->fetch();

                $stmt->free_result();

                $stmt->close();

                /** @var int $web_id */
                return $web_id;
            } else {

                $stmt->free_result();

                $stmt->close();
                return false;
            }
        } else {
            $this->error = "MySql Error: " . $this->connection->error;
        }
    }

    function getWebNames($username)
    {
        $sql = "SELECT (task_name) FROM Tasks WHERE username = ? AND web_id > 0;";

        if ($stmt = $this->connection->prepare($sql)) {
            $stmt->bind_param('s', $username);

            $stmt->execute();

            $stmt->store_result();
            if ($stmt->num_rows !== 0) {
                $stmt->bind_result($name_row);
                while ($stmt->fetch()) {
                    $names[] = $name_row;
                }

                $stmt->free_result();

                $stmt->close();

                /** @var array $names */
                return $names;

            } else {

                $stmt->free_result();

                $stmt->close();
                $this->error = "MySql Error: no rows in table";
            }
        }
        $this->error = "MySql Error: user does not exist";
    }

    function getWebDesc($username)
    {
        $sql = "SELECT (task_description) FROM Tasks WHERE username = ? AND web_id > 0;";

        if ($stmt = $this->connection->prepare($sql)) {
            $stmt->bind_param('s', $username);

            $stmt->execute();

            $stmt->store_result();
            if ($stmt->num_rows !== 0) {
                $stmt->bind_result($desc_row);
                while ($stmt->fetch()) {
                    $desc[] = $desc_row;
                }

                $stmt->free_result();

                $stmt->close();

                /** @var array $desc */
                return $desc;
            } else {

                $stmt->free_result();

                $stmt->close();
                $this->error = "MySql Error: no rows in table";
            }
        }
        $this->error = "MySql Error: user does not exist";
    }

    function getWebTaskID($username)
    {
        $sql = "SELECT (identifier) FROM Tasks WHERE username = ? AND web_id > 0;";

        if ($stmt = $this->connection->prepare($sql)) {
            $stmt->bind_param('s', $username);

            $stmt->execute();

            $stmt->store_result();
            if ($stmt->num_rows !== 0) {
                $stmt->bind_result($id_row);
                while ($stmt->fetch()) {
                    $ids[] = $id_row;
                }

                $stmt->free_result();

                $stmt->close();

                /** @var array $ids */
                return $ids;
            } else {

                $stmt->free_result();

                $stmt->close();
                $this->error = "MySql Error: no rows in table";
            }
        }
        $this->error = "MySql Error: user does not exist";
    }

    function getWebDueDate($username)
    {
        $sql = "SELECT (due_date) FROM Tasks WHERE username = ? AND web_id > 0;";

        if ($stmt = $this->connection->prepare($sql)) {
            $stmt->bind_param('s', $username);

            $stmt->execute();

            $stmt->store_result();
            if ($stmt->num_rows !== 0) {
                $stmt->bind_result($duedate_row);
                while ($stmt->fetch()) {
                    $duedates[] = $duedate_row;
                }

                $stmt->free_result();

                $stmt->close();

                /** @var array $duedates */
                return $duedates;
            } else {

                $stmt->free_result();

                $stmt->close();
                $this->error = "MySql Error: no rows in table";
            }
        }
        $this->error = "MySql Error: user does not exist";
    }

    function getWebStates($username)
    {
        $sql = "SELECT (is_done) FROM Tasks WHERE username = ? AND web_id > 0;";

        if ($stmt = $this->connection->prepare($sql)) {
            $stmt->bind_param('s', $username);

            $stmt->execute();

            $stmt->store_result();
            if ($stmt->num_rows !== 0) {
                $stmt->bind_result($state_row);
                while ($stmt->fetch()) {
                    $states[] = $state_row;
                }

                $stmt->free_result();

                $stmt->close();

                /** @var array $states */
                return $states;
            } else {

                $stmt->free_result();

                $stmt->close();
                $this->error = "MySql Error: no rows in table";
            }
        }
        $this->error = "MySql Error: user does not exist";
    }

    function updateState($task_id, $state)
    {
        $sql = "UPDATE Tasks SET is_done='$state' WHERE identifier = '$task_id';";

        if ($this->connection->query($sql) === false) {
            $this->error = "Mysql error: " . $this->connection->error;
        }
    }
}

