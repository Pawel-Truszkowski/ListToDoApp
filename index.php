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

#Update tasków

if (isset($_GET['update'])) {
    $id = $_GET['update'];
    $updateQuery = $db->prepare("UPDATE tasks SET task = :task WHERE id = :id");
    $updateQuery->execute(['task' => $task, 'id' => $id]);
    header('Location: index.php');
}

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
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
</head>

<body>
    <header class="container">
        <div class="row">
            <h1 class="col-md-12 text-left">List To-Do App</h1>
        </div>
    </header>
    <section class="container">
        <div class="row">
            <div class="col-sm-12">
                <form method="post" action="index.php">
                    <p><label for="fname">Save your task:</label></p>
                    <textarea id="w3review" name="task" rows="3" cols="50" required></textarea>
                    <br>
                    <?php
                    if (isset($error)) ?>
                    <p><?= $error; ?> </p>
                    <button type="submit" name="submit">Submit</button>
                </form>
            </div>
        </div>
    </section>
    <section class="container">
        <div class="row">
            <div class="col-sm-12">
                <h2>Current Tasks</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <table class="table">
                    <thead>
                        <tr class="table">
                            <th>Nr</th>
                            <th>Task</th>
                            <th>Update</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count = 1;
                        foreach ($list as $task) {
                        ?>
                            <tr class="table">
                                <td class="nr"><?= $count ?></td>
                                <td class="task"><?= $task['task'] ?></td>
                                <td class="update">
                                    <a href="index.php?update=<?= $task['id']; ?>">
                                        <span class="material-symbols-outlined">
                                            update
                                        </span>
                                    </a>
                                </td>
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
    </section>
</body>

</html>