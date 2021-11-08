<?php 

    require "../private/autoload.php";

    $user_data = check_login($con);
    
    $user_name = "";
    if(isset($_SESSION['user_name'])) {
        $user_name = $_SESSION['user_name'];
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://kit.fontawesome.com/a076d05399.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css">
        <link rel="stylesheet" href="styling.css">
        <title>MUSICSTORE | E-COMMERCE WEBSITE</title>
    </head>
    <body>
        <div id="container">
            <nav id="header">
                <input type="checkbox" id="check">
                <label for="check" class="checkbtn">
                    <i class="fa fa-bars"></i>
                </label>
                <a class="logo" href="index.php">Music<span style="color:#1b9bff;">STORE</span>&trade;</a>
                <ul class="nav-list">
                    <li><a class="active" href="index.php">Home</a></li>
                    <li><a class="active" href="#">Categories</a></li>
                    <li><a class="active" href="#">About</a></li>
                    <li><a class="active" href="#">Contact</a></li>
                    <?php
                        if($user_name != "") {
                    ?>
                    <li> 
                        <a class="active" href="#"><?=$_SESSION['user_name']?></a>
                        <ul class="sub-list-account">
                            <li><a href="#">My Profile</a></li>
                            <li><a href="#">Orders</a></li>
                            <li><a href="#">Cart</a></li>
                            <hr>
                            <li><a href="logout.php">Sign out</a></li>
                        </ul></li>
                    </li>
                    <?php
                        } else {
                    ?>
                    <li class="submenu">
                        <a class="active" href="login.php">Sign in</a>
                    <?php
                        }
                    ?>
                </ul>                
            </nav>
            <section>
                <!-- <h1 class="ml2">Welcome to MusicSTORE</h1>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/2.0.2/anime.min.js"></script>   
                <script>
                    // Wrap every letter in a span
                    var textWrapper = document.querySelector('.ml2');
                    textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");

                    anime.timeline({loop: true})
                    .add({
                        targets: '.ml2 .letter',
                        scale: [4,1],
                        opacity: [0,1],
                        translateZ: 0,
                        easing: "easeOutExpo",
                        duration: 950,
                        delay: (el, i) => 70*i
                    }).add({
                        targets: '.ml2',
                        opacity: 0,
                        duration: 1000,
                        easing: "easeOutExpo",
                        delay: 1000
                    });
                </script> -->
            </section>
    </body>
</html>