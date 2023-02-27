<?php

require_once 'db_config';

$login = $_POST['login'];
$password = $_POST['password'];

echo $login . "  " . $password;
