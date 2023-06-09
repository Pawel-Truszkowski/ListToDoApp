<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/autoload.php';

error_reporting(E_ALL & ~E_NOTICE); //Hide notices

#Sprawdzamy czy uytkownik czasem nie jest zalogowany juz
if ($_SESSION['logged_in']) {
    header('Location: todo.php');
    exit();
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


        $secret = "{SECRET KEY}";
        $response = $_POST['g-recaptcha-response'];

        $check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $response);

        $result = json_decode($check);

        if (($result->success) == false) {
            $okey = false;
            $_SESSION['e_bot'] = "Confirm that you are not a bot!";
        }

        function getToken($len = 32)
        {
            return substr(md5(openssl_random_pseudo_bytes(20)), -$len);
        }

        $token = getToken(10);
    }

    //łaczenie z bazą 
    require_once "db_config.php";

    try {
        $db = connect();

        if (!$db) {
            throw new PDOException('Database Error');
        } else {
            # Sprawdzanie czy istnieje nick
            $userQuery = $db->query("SELECT user_id FROM users WHERE user='$nick'");
            $user = $userQuery->fetch();

            if (!$userQuery) throw new PDOException($db->errorCode());

            $exist_nick = $userQuery->rowCount();
            if ($exist_nick > 0) {
                $okey = false;
                $_SESSION['e_nick'] = "Such a nickname already exists. Please choose another one.";
            }

            # Sprawdzanie czy istnieje e-mail
            $emailQuery = $db->query("SELECT email FROM users WHERE email='$email'");
            $user_email = $emailQuery->fetch();

            if (!$emailQuery) throw new PDOException($db->errorCode());

            $exist_email = $emailQuery->rowCount();
            if ($exist_email > 0) {
                $okey = false;
                $_SESSION['e_email'] = "Such a e-mail already exists. Please choose another one.";
            }

            if ($okey == true) {
                if ($db->query("INSERT INTO users VALUES (NULL, '$nick', '$password_hash', '$email', '$token', 0)")) {
                    $_SESSION['success_reg'] = true;

                    // Wysyłka maila
                    try {
                        //Create an instance; passing `true` enables exceptions
                        $mail = new PHPMailer(true);

                        //Server settings
                        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                        $mail->isSMTP();                                            //Send using SMTP
                        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                        $mail->Username   = 'send.email.to.client@gmail.com';                     //SMTP username
                        $mail->Password   = 'XXXXXX';                               //SMTP password
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                        //Recipients
                        $mail->setFrom('send.email.to.client@gmail.com', 'User Registration');
                        $mail->addAddress($email);     //Add a recipient


                        $mail->isHTML(true);                                  //Set email format to HTML
                        $mail->Subject = 'Confirm e-mail';
                        $mail->Body    = 'Activate your e-mail: 
                        <a href="http://localhost/ListTodo/verification.php?email=' . $email . '&token=' . $token . '"> Click on the link </a>';

                        $mail->send();
                    } catch (Exception $e) {
                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }

                    header('Location: welcome.php');
                } else {
                    throw new PDOException($db->errorCode());
                }
            }
        }
    } catch (PDOException $e) {
        echo '<span style = "color: red;"> Database error. Please come back later.</span>';
        echo '</br> Developer information: ' . $e->getMessage();
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
    <script src="https://www.google.com/recaptcha/api.js"></script>


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
                <div class="text-center">
                    <div class="g-recaptcha" data-sitekey="{SITE KEY}"></div>
                </div>

                <?php
                if (isset($_SESSION['e_bot'])) {
                    echo '<div id="error">' . $_SESSION['e_bot'] . '</div>';
                    unset($_SESSION['e_bot']);
                }
                ?>

                <br><input type="submit" value="Sing-up" />
            </form>
        </div>
    </section>

</body>

</html>