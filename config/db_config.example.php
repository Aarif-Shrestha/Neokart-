<?php

// Copy this file to config/db_config.php and fill in your local values.
$server = "127.0.0.1";
$username = "your_db_user";
$password = "your_db_password";
$database = "your_db_name";

$conn = new mysqli(
    $server,
    $username,
    $password,
    $database
);

$isError = $conn->connect_error;

?>
