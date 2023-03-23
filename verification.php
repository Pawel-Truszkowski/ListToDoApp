<?php
session_start();

require_once "db_config.php";

if ($_GET) {
    if (isset($_GET['email'])) {
        $email = $_GET['email'];
        if ($email == '') unset($email);
    }

    if (isset($_GET['token'])) {
        $token = $_GET['token'];
        if ($token == '') unset($token);
    }

    if (!empty($email) && !empty($token)) {
        try {
            $db = connect();
            $select = $db->prepare("SELECT user_id FROM users WHERE email=:email AND token=:token");
            $select->execute(['email' => $email, 'token' => $token]);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        if ($select->fetch() > 0) {
            try {
                $update = $db->prepare("UPDATE users SET confirmation=1, token='' WHERE email=:email");
                $update->execute(['email' => $email]);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
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
    <section class="container">
        <div class="row">
            <h2>Thank you for registering on the site! You can already log in to your account</h2> <br><br>
            <a href="index.php">Log-in to your account!</a>
        </div>
    </section>
</body>

</html>