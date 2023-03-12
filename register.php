<?php
session_start();

require_once 'db_config.php';
$db = connect();


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
            <a href="index.php">Back</a>
        </div>
        <div class="row">
            <h3>Sorry, registration will be available soon. Please come back later.</h3>
        </div>
    </header>
    <!--    
    <section class="container">
        <form method="post">
            Login: <br /> <input type="text" name="nick" /> </br>

        </form>
    </section>
-->
</body>

</html>