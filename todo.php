<?php

session_start();

if (!isset($_SESSION['logged_in']))   #jesli zmienna nie jest ustawiona to przekieruj do index.php
{
    header('Location: index.php');
    exit();
}

require_once 'db_config.php';

$db = connect();


$task = filter_input(INPUT_POST, 'task', FILTER_DEFAULT);
#$task = $_POST['task'];
$error = "";

if (isset($_POST['submit'])) {
    if (!empty($_POST['task'])) {
        try {
            $insertQuery = $db->prepare("INSERT INTO tasks (user_id, task, status) VALUES (:user_id, :task, 0)");
            $insertQuery->execute(['user_id' => $_SESSION['user_id'], 'task' => $task]);
            header('Location: todo.php');
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        $error = "You have to asign the task!";
    }
}

$listQuery = $db->prepare("SELECT * FROM tasks WHERE user_id = :user_id ORDER BY tasks.status ASC");
$listQuery->execute(['user_id' => $_SESSION['user_id']]);
$list = $listQuery->fetchAll(PDO::FETCH_ASSOC);

#Update tasków - ustawiamy status na 1 jesli zadanie zostało zrobione
if (isset($_GET['check'])) {
    $id = $_GET['check'];
    try {
        $updateQuery = $db->prepare("UPDATE tasks SET status=1 WHERE task_id = :id");
        $updateQuery->execute(['id' => $id]);
        header('Location: todo.php');
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

#Usuwamy zadanie
if (isset($_GET['delete_task'])) {
    $id = $_GET['delete_task'];
    try {
        $deleteQuery = $db->prepare("DELETE FROM tasks WHERE task_id = :id");
        $deleteQuery->execute(['id' => $id]);
        header('Location: todo.php');
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
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
            <a href="logout.php">Logout!</a>
        </div>
        <div class="row">
            <h1>List To-Do App</h1>
        </div>
    </header>
    <section class="container">
        <div class="row">
            <div class="col-sm-12">
                <form method="post" action="todo.php">
                    <p><label for="fname">Save your task:</label></p>
                    <textarea name="task" rows="3" cols="50"></textarea><br>
                    <button type="submit" name="submit" id="submit">Submit</button>
                    <?php
                    if (isset($error)) ?>
                    <p id="error"><?= $error; ?> </p>
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
                            <th>Status</th>
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
                                <td class="status">
                                    <?php
                                    if ($task['status'] == 1) {
                                        echo "Done";
                                    } else { ?>
                                        <a href="todo.php?check=<?= $task['task_id']; ?>" class="btn btn-success"><span class="glyphicon glyphicon-check">OK</span></a>
                                    <?php
                                    }
                                    ?>
                                </td>
                                <td class="delete">
                                    <a href="todo.php?delete_task=<?= $task['task_id']; ?>" class="btn btn-danger">X</a>
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