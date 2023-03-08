<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once "db_config.php";

#Jesli zmienna login i haslo nie sa ustawione to znaczy ze uzytkownik musi sie zalogowac czyli musi wypelnic formularz
if ((!isset($_POST['login'])) || (!isset($_POST['password']))) {
    header('Location: index.php');
    exit();
}

$db = connect();

$login = $_POST['login'];
$login = htmlentities($login, ENT_QUOTES, "UTF-8"); //Sprawdzanie poprawnosci danych wprowadzonych przez uzytkownika
$password = $_POST['password'];

try {
    $userQuery = $db->prepare("SELECT * FROM users WHERE user=:user");
    $userQuery->bindValue(':user', $login, PDO::PARAM_STR);
    $userQuery->execute();
    $user = $userQuery->fetch();
} catch (Exception $e) {
    echo $e->getMessage();
}

if ($userQuery->rowCount() > 0) {
    #Jesli jest wiecej uzytkownikow niz 0 a w zasadzie nie powinno byc wiecej niz 1 to go logujemy
    #czyli przypisujemy zamienne do sesji
    if ($password === $user['password']) {
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user'] = $user['user'];
        $_SESSION['email'] = $user['email'];

        header('Location: todo.php');
    } else {
        $_SESSION['error'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
        header('Location: index.php');
    }
} else {
    $_SESSION['error'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
    header('Location: index.php');
}
