<?php    

    require "../private/autoload.php";

    // if(isset($_SESSION['user_id'])) {
    //     unset($_SESSION['user_id']);
    // }
    // if(isset($_SESSION['user_name'])) {
    //     unset($_SESSION['user_name']);
    // }

    session_unset();
    session_destroy();

    // header("Location: login.php");
    header("Location: index.php");
    die;
?>