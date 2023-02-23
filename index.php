<?php

require 'db_config.php';

$db = connect();

$task = $_POST['task'];

$error = "";

if (isset($_POST['submit'])) {
    if (!empty($_POST['task'])) {
        try {
            $insertQuery = $db->prepare("INSERT INTO tasks (task) VALUES (:task)");
            $insertQuery->execute(['task' => $task]);
            header('Location: index.php');
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            echo "Nie udało się dodać zadania do listy.";
        }
    } else {
        $error = "Musisz podać jakąś wartość aby wpisać zadanie na listę!";
    }
}

$listQuery = $db->prepare("SELECT * FROM tasks");
$listQuery->execute();
$list = $listQuery->fetchAll(PDO::FETCH_ASSOC);

#Usuwamy zadanie
if (isset($_GET['delete_task'])) {
    $id = $_GET['delete_task'];
    $deleteQuery = $db->prepare("DELETE FROM tasks WHERE id = :id");
    $deleteQuery->execute(['id' => $id]);
    header('Location: index.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List To-Do App</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>
    <div class="container">
        <h1>List To-Do App</h1>
        <form method="post" action="index.php">
            <label for="fname">Save your task:</label>
            <input type="text" name="task" placeholder="This field is required">
            <?php
            if (isset($error)) ?>
            <p><?= $error; ?> </p>
            <button type="submit" name="submit">Submit</button>
        </form>

        <h2>Current Tasks</h2>
        <div class="array">
            <table>
                <thead>
                    <tr>
                        <th>Nr</th>
                        <th>Task</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $count = 1;
                    foreach ($list as $task) {
                    ?>
                        <tr>
                            <td class="nr"><?= $count ?></td>
                            <td class="task"><?= $task['task'] ?></td>
                            <td class="delete">
                                <a href="index.php?delete_task=<?= $task['id']; ?>">X</a>
                            </td>
                        </tr>
                    <?php $count++;
                    } ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>