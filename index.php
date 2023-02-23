<?php

#echo "Hello World!";

require_once 'db_config.php';

$db = connect();

$task = $_POST['task'];

if (isset($_POST['submit'])) {
    try {
        $insertTask = $db->prepare('INSERT INTO tasks (task) VALUES (:task)');
        $insertTask->execute(['task' => $task]);
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        echo "Nie udało się dodać zadania do listy.";
    }
}

$listQuery = $db->prepare('SELECT * FROM tasks');
$listQuery->execute();
$list = $listQuery->fetchAll(PDO::FETCH_ASSOC);

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
                    <button type="submit" name="submit">Zapisz</button>
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
                        <?php
                        foreach ($list as $task) { ?>
                            <tr>
                                <td><?= $task['id'] ?></td>
                                <td><?= $task['task'] ?></td>
                                <td>Active</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</body>

</html>