<?php

#echo "Hello World!";

require_once 'db_config.php';

$db = connect();

$task = $_POST['task'];

if (!empty($_POST)) {
    $insert = $db->prepare('ISERT INTO listtodo (task) VALUES (:task)');
    $insert->execute(['task'=>$task]);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List To-Do App</title>
</head>

<body>
    <div class="container">
        <div class="container">
            <h1>List To-Do App</h1>

            <div class="form">
                <form method="post" action="index.php">
                    <label for="fname">Wpisz zadanie na listę:</label>
                    <input type="text" name="task"><br><br>
                    <input type="submit" value="Zapisz"><br><br>
                </form>
            </div>
            <h2>Current Tasks</h2>
            <div class="array">
                <table>
                    <thead>
                        <tr>
                            <th>Nr</th>
                            <th>Task</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Pierwsze testowe zadanie</td>
                            <td>Active</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</body>

</html>