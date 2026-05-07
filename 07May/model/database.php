<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'Library Management');

function db_connect()
{
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (!$conn) {
        die('Database connection error: ' . mysqli_connect_error());
    }
    // set charset
    mysqli_set_charset($conn, 'utf8mb4');
    return $conn;
}

?>
