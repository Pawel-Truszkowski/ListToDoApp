<?php

function connect()
{
    $host = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "listtodo";
    $dsn = "mysql:host=$host;dbname=$db_name";

    try {
        $db = new PDO($dsn, $db_user, $db_password);
        return $db;
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit('Database error');
    }
}
