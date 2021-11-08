<?php
    define('DB_HOST', "localhost:3308"); // check the port of MySQL which you are using
    define('DB_USER', "root");
    define('DB_PASS', "");
    define('DB_NAME', "dbmsproject");

    // $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // if(!$con) {
    //     die("Failed to connect!");
    // }

    $string = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
    $con = new PDO($string, DB_USER, DB_PASS);
    if(!$con) {
        die("Failed to connect!");
    }

    // $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    // if($con->connect_error) {
    //     die('Connection Failed: '.$con->connect_error);
    // }
?>