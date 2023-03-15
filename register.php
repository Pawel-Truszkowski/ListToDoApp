<?php
session_start();

require_once 'db_config.php';
$db = connect();

#Sprawdzamy czy uytkownik czasem nie jest zalogowany juz
if ($_SESSION['logged_in']) {
    echo '<p><b><span style="color:red">You are already logged in.</span></b></p>
		<p><a href="index.php">Back</a></p>';
} else {
    if (isset($_POST['email'])) {

        $okey = true; #zakladam ze wsyzstko bedzei zgodne

        $nick = filter_input(INPUT_POST, 'nick', FILTER_DEFAULT);


        if ((strlen($nick) < 3) || (strlen($nick) > 20)) {
            $okey = false;
            $_SESSION['e_nick'] = "The nickname must contain between 3 and 20 characters.";
        }

        //Sprawdzenie poprawności nicka czyli z poprawnymi znakami
        if (ctype_alnum($nick) == false) {
            $okey = false;
            $_SESSION['e_nick'] = "Nickname can only consist of letters and numbers.";
        }

        //Sprawddzenie poprawności e-maila
        $email = $_POST['email'];
        $emailB = filter_var($email, FILTER_SANITIZE_EMAIL); //Funkcja sprawdzająca poprawność maila

        if ((filter_var($emailB, FILTER_VALIDATE_EMAIL) == false) || ($emailB != $email)) {
            $okey = false;
            $_SESSION['e_email'] = "Provide a valid email address";
        }


        //Sprawdź poprawność hasła
        $password1 = $_POST['password1'];
        $password2 = $_POST['password2'];

        if ((strlen($password1) < 8) || (strlen($password1) > 20)) {
            $okey = false;
            $_SESSION['e_password'] = "The password must contain between 8 and 20 characters.";
        }

        if ($password1 != $password2) {
            $okey = false;
            $_SESSION['e_password'] = "The passwords provided are not identical";
        }

        //Zahashowanie hasła
        $password_hash = password_hash($password1, PASSWORD_DEFAULT);
    }

    //łaczenie z bazą 
    require_once "db_config.php";

    try {
        $db = connect();

        if (!$db) {
            throw new Exception('Database Error');
        } else {
            $userQuery = $db->prepare("SELECT user_id FROM users WHERE user=:nick");
            $userQuery->bindValue(':user', $nick, PDO::PARAM_STR);
            $userQuery->execute();
            $user = $userQuery->fetch();

            
        }
    } catch (Exception $e) {
        echo '<span style = "color: red;"> Błąd serwera! Przepraszamy za niedogodności i zapraszamy ponownie w innym terminie.</span>';
        echo '</br> Informacja developerska: ' . $e;
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
            <a href="index.php">Back</a>
        </div>
        <div class="row">
            <h3>Register your free account!</h3>
        </div>
    </header>
    <section class="container">
        <div class="row">
            <form method="post">
                Login: <br /> <input type="text" name="nick" /> </br>
                <?php
                if (isset($_SESSION['e_nick'])) {
                    echo '<div id="error">' . $_SESSION['e_nick'] . '</div>';
                    unset($_SESSION['e_nick']);
                }
                ?>
                E-mail: <br /> <input type="text" name="email" /> </br>
                <?php
                if (isset($_SESSION['e_email'])) {
                    echo '<div id="error">' . $_SESSION['e_email'] . '</div>';
                    unset($_SESSION['e_email']);
                }
                ?>
                Password: <br /> <input type="password" name="password1" /> </br>
                Confirm password: <br /> <input type="password" name="password2" /> </br>
                <?php
                if (isset($_SESSION['e_password'])) {
                    echo '<div id="error">' . $_SESSION['e_password'] . '</div>';
                    unset($_SESSION['e_password']);
                }
                ?>
                <br><input type="submit" value="Sing-up" />
            </form>
        </div>
    </section>

</body>

</html>