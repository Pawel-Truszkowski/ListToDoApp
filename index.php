<?php

#echo "Hello World!";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List To-Do App</title>
</head>

<body>
    <div class="container">
        <div class="container">
            <h1>List To-Do App</h1>

            <form method="post" action="tasks.php">
                Wpisz zadanie na listę: <br>
                <input type="text" name="task">
                <input type="submit">

            </form>
        </div>

        <p></p>
    </div>

</body>

</html>