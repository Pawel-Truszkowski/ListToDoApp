<?php
session_start();

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

</body>
<header class="container">
    <div class="row">
        <h1>List To-Do App</h1>
    </div>
</header>
<section class="container">
    <div class="row justify_content-center">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <p>If you would like to organize your tasks just log-in and use the List To-Do App. Write down, complete tasks and enjoy your tasks now!</p>
            <p>If you don't have an account yet, please <a href="register.php">sign up!</a></p>
        </div>
    </div>
    <div class="row justify_content-center">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <form action="login.php" method="post">
                <span>Login: </span><br>
                <input type="text" name="login" /><br>
                <span>Password: </span><br>
                <input type="password" name="password" /><br>
                <br> <input type="submit" value="Log-in" />
            </form>
        </div>
    </div>
</section>

</html>