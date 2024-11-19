<?php
    $host = "127.0.0.1";
    $user = "root";               // Default MySQL user in XAMPP is "root"
    $pass = "";                   // Leave blank if there’s no password for "root"
    $db = "db_movie";             // Database name you created in Step 3
    $port = 3306;

    $con = mysqli_connect($host, $user, $pass, $db, $port);

    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>
