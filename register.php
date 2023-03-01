<?php
session_start();

$db = connect();

$login = $_POST['login'];
$login = htmlentities($login, ENT_QUOTES, "UTF-8"); //Sprawdzanie poprawnosci danych wprowadzonych przez uzytkownika
$password = $_POST['password'];

$userQuery = $db->prepare("SELECT * FROM users WHERE user=:user");
//$userQuery->bindValue(':user', $login, PDO::PARAM_STR);
$userQuery->execute([':user' => $login]);
$user = $userQuery->fetchAll(PDO::FETCH_ASSOC);
$numberUsers = count($user);

echo $numberUsers;
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

</html>