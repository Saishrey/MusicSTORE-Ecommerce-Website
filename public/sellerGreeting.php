<?php 

    require "../private/autoload.php";

    $guitar = "Guitar";
    $piano = "Piano";
    $keyboard = "Keyboard";
    $drums = "Drums and Percussions";
    $wind = "Wind instruments";
    
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
        <!-- <link rel="stylesheet" href="styling.css"> -->
        <title>Seller | MusicSTORE</title>
    </head>
    <body>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Roboto:wght@100&family=Zen+Kaku+Gothic+Antique&display=swap');
            * {
                padding: 0;
                margin: 0;
                box-sizing: border-box;
            }
            
            body {
                /* background: url(images/music.jpg) no-repeat; */
                background-size: cover;
                font-family: 'Montserrat', sans-serif;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }
            header {
                /* background: #0082e6; */
                /* background: #181818; */
                background: rgba(24,24,24, 0.97);
                position: fixed;
                /* opacity: 0.7; */
                height: 80px;
                width: 100%;
                border-bottom: 1px solid #1b9bff;
                z-index: 2;
            }
            .logo {
                color: white;
                font-size: 35px;
                line-height: 80px;
                margin-left: 100px;
                font-weight: bold;
                text-decoration: none;
                transition: font-size 0.2s;
            }
            .logo sub {
                font-size: 16px;
            }
            .logo:hover {
                font-size: 36px;
            }
            header > ul {
                list-style: none;
                position: relative;
                float: right;
                margin-right: 100px;
            }
            header > ul > li {
                display: inline-block;
                position: relative;
                line-height: 80px;
                margin: 0 5px;
            }
            header ul ul {
                display: none;
                position: absolute;
                /* background: #181818; */
                background: rgba(24,24,24, 0.9);
                list-style: none;
                line-height: 50px;
                top: 100%;
                left: 0;
                width: 200px;
            }
            header ul li:hover > ul {
                display: block;
            }
            header ul ul > li:hover {
                background: #1b9bff;
                transition: .5s;
            }
            header > ul li a {
                color: white;
                font-size: 17px;
                padding: 30px 10px;
                border-radius: 3px;
                text-decoration: none;
                text-transform: uppercase;
            }
            header > ul > li:hover {
                background: #1b9bff;
                transition: 0.5s;
            }
            .checkbtn {
                font-size: 30px;
                color: white;
                float: right;
                line-height: 80px;
                margin-right: 40px;
                cursor: pointer;
                display: none;
            }
            #check {
                display: none;
            }
            @media (max-width: 1500px) {
                .logo {
                    font-size: 30px;
                    margin-left: 50px;
                }
                header .nav-list li a {
                    font-size: 15px;
                }
            }
            @media (max-width: 1200px) {
                .checkbtn {
                    display: block;
                }
                .checkbtn:hover {
                    color: #1b9bff;
                }
                header > ul {
                    position: fixed;
                    width: 100%;
                    height: 100vh;
                    background: #2c3e50;
                    /* background: rgba(24,24,24, 0.9); */
                    z-index: 2;
                    top: 80px;
                    left: -100%;
                    text-align: center;
                    transition: all 0.25s;
                }
                header ul > li {
                    display: block;
                    margin: 50px 0;
                    line-height: 30px;
                }
                header .nav-list li a {
                    font-size: 20px;
                }
                header ul li:hover {
                    background: none;
                }
                header ul li a:hover {
                    background: none;
                    color: #0082e6;
                }
                header ul li:hover > ul {
                    display: none;
                }
                #check:checked ~ ul {
                    left: 0;
                }
            }
            main {
                /* background: url(images/sellerbg3_blur.jpg) no-repeat; */
                /* background-size: cover; */
                /* background: whitesmoke; */
                background: url(images/bg_img1.png) no-repeat;
                background-size: cover;
                min-width: 750px;
            }
            @keyframes greeting-anim {
                0% { clip-path: ellipse(15% 100% at 50% 0%);}
                25% {clip-path: ellipse(30% 100% at 50% 0%);}
                50% {clip-path: ellipse(45% 100% at 50% 0%);}
                75% {clip-path: ellipse(60% 100% at 50% 0%);}
                100% {clip-path: ellipse(75% 100% at 50% 0%);}
            }
            main #greeting {
                margin-top: 80px;
                color: white;
                text-align: center;
                /* background: url(images/sellerbg3.jpg) no-repeat; */
                /* background-size: cover; */
                background: rgb(5,29,54);
                clip-path: ellipse(75% 100% at 50% 0%);
                padding: 100px;
                animation-name: greeting-anim;
                animation-duration: 400ms;
                animation-timing-function: linear;
            }
            main #greeting h1 {
                font-size: 90px;              
            }
            main #greeting h2{
                color: whitesmoke;
                font-size: 50px;
                font-weight: 100;
                margin-bottom: 60px;
            }
            main #greeting h3 {
                font-size: 55px;
            }
            main #greeting h3 span {
                color: #1b9bff;
            }
            main #greeting h4 {
                font-size: 24px;
            }
            @keyframes register-box-anim {
                0% {transform: rotateX(90deg);}
                25% {transform: rotateX(60deg);}
                50% {transform: rotateX(45deg);}
                75% {transform: rotateX(30deg);}
                100% {transform: none;}
            }
            main #register-box {
                display: flex;
                flex-direction: row;
                margin: auto;
                margin-top: 100px;
                margin-bottom: 100px;
                /* box-shadow: 10px 10px 15px rgba(24,24,24, 0.9); */
                box-shadow: 0 5px 25px rgba(21,34,58,.13);
                width: 65%;
                padding: 40px;
                /* max-width: 1200px; */
                background: white;
                border-radius: 6px;
                animation-name: register-box-anim;
                animation-duration: 400ms;
                animation-timing-function: linear;
            }
            main #register-box .seller-container {
                /* margin: 20px; */
                margin: auto;
                text-align: right;
                /* padding: 100px; */
                color: rgb(6,32,60);
            }
            main #register-box .seller-container .for-registered a {
                padding: 15px 60px;
                width: 80%;
                /* background-color: #32AEF2; */
                /* background: #181818; */
                background: rgb(5,29,54);
                text-decoration: none;
                color: white;
                font-size: 20px;
                font-family: 'Montserrat', sans-serif;
                text-transform: uppercase;
                outline: none;
                border-radius: 6px;
            }
            main #register-box .seller-container .for-registered a:hover {
                background: #1b9bff;
                transition: 0.25s;
            } 
            main #register-box .seller-container .not-registered a {
                padding: 15px 60px;
                width: 80%;
                /* background-color: #32AEF2; */
                /* background: #181818; */
                background: grey;
                text-decoration: none;
                color: whitesmoke;
                font-size: 20px;
                font-family: 'Montserrat', sans-serif;
                text-transform: uppercase;
                outline: none;
                border-radius: 6px;
            }
            main #register-box img {
                width: 50%;
                max-width: 1000px;
                height: auto;
            }
            main .closing {
                text-align: center;
                margin: 80px;
                color: white;
                font-size: 22px;
            }
            main .closing a {
                text-decoration: none;
                color: #14279B;
            }
            main .closing a:hover {
                color: #38A3A5;
                transition: 0.25s;
            }
            @media(max-width: 1200px) {
                main #register-box .seller-container {
                    width: 100%;
                    text-align: center;
                }
                main #register-box img {
                    display: none;
                }
                main {
                    background: whitesmoke;
                }
                main .closing {
                    color: black;
                }
            }
            
            /* footer */
            footer {
                margin-top: auto;
            }
            .container{
                max-width: 1170px;
                margin: auto;
            }
            .row{
                display: flex;
                flex-wrap: wrap;
            }
            footer ul{
                list-style: none;
            }
            .footer{
                background-color: #24262b;
                padding: 60px 0;
            }
            .footer-col{
                width: 25%;
                padding: 0 15px;
            }
            .footer-col p{
                font-size: 15px;
                color: white;
                width: 50%;
                text-transform: capitalize;
                margin-bottom: 35px;
                padding-bottom: 5px;
                border-bottom: 2px solid #1b9bff;
                /* font-weight: 500; */
                /* position: relative; */
            }
            /* .footer-col p::before{
                content: '';
                position: absolute;
                left:0;
                bottom: -10px;
                background-color: #1b9bff;
                height: 2px;
                box-sizing: border-box;
                width: 50px;
            } */
            .footer-col ul li:not(:last-child){
                margin-bottom: 10px;
            }
            .footer-col ul li a{
                font-size: 13px;
                text-transform: capitalize;
                color: #ffffff;
                text-decoration: none;
                font-weight: 300;
                color: #bbbbbb;
                display: block;
                transition: all 0.3s ease;
            }
            .footer-col ul li a:hover{
                color: #1b9bff;
                padding-left: 8px;
            }
            .footer-col .social-links a{
                display: inline-block;
                height: 40px;
                width: 40px;
                background-color: rgba(255,255,255,0.2);
                margin:0 10px 10px 0;
                text-align: center;
                line-height: 40px;
                border-radius: 50%;
                color: #ffffff;
                transition: all 0.5s ease;
            }
            .footer-col .social-links a:hover{
                color: #24262b;
                background-color: #ffffff;
            }

            /*responsive*/
            @media(max-width: 767px){
                .footer-col{
                    width: 50%;
                    margin-bottom: 30px;
                }
            }
            @media(max-width: 574px){
                .footer-col{
                    width: 100%;
                }
            }
        </style>
            <header id="header">
                <input type="checkbox" id="check">
                <label for="check" class="checkbtn">
                    <i class="fa fa-bars"></i>
                </label>
                <a class="logo" href="sellerGreeting.php">Music<span style="color:#1b9bff;">STORE</span>&trade;<sub> Seller</sub></a> <!-- &trade; -->
                <ul class="nav-list">
                    <li><a class="active" href="index.php">Home</a></li>
                    <li> 
                        <a class="active" href="index.php"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                    </li>
                </ul>                
            </header>
            <main id="top">
                <div id="greeting">
                    <h1>Welcome!</h1>
                    <h2><?=$user_name?></h2>
                    <h4>Begin Your Selling Journey On</h4>
                    <h3>Music<span>STORE</span>&trade;</h3>
                </div>
                <div id="register-box">
                    <div class="seller-container">
                            <h1>SELLER<br><br>Grow your store <br><span style="font-size:18px;">Start selling with MusicSTORE</span></h1>
                            <?php
                                if($user_name != "") {
                            ?>
                            <br>
                            <br>
                            <p class="for-registered"><a href="sellerRegistration.php">Register Here</a></p>
                            <?php
                                } else {
                            ?>
                            <br>
                            <br>
                            <p class="not-registered"><a title="You need to Sign in">Register Here</a></p>
                            <?php
                                }
                            ?>
                    </div>
                    <!-- <div class="seller-container">
                        <img src="images/supplier-pc-img.png" alt="#" />
                    </div> -->
                    <img src="images/supplier-pc-img.png" alt="#" />

                </div>
                <?php
                    if($user_name == "") {
                ?>
                    <p class="closing"><a href="login.php">Sign in</a><br> Don't have an account? <a href="signup.php">Sign up</a></p>
                <?php
                    } else {
                ?>
                    <p class="closing">Would like to create a new account? <a href="signup.php" target="_blank">Click here</a></p>
                <?php
                    }
                ?>
            </main>
            <footer class="footer">
                <div class="container">
                    <div class="row">
                        <div class="footer-col">
                            <p>Account</p>
                            <ul>
                                <?php
                                    if($user_name == "") {
                                ?>
                                    <li><a href="signup.php">Customer</a></li>
                                <?php
                                    } else {
                                ?>
                                    <li><a href="useraccount.php">Customer</a></li>
                                <?php
                                    }
                                ?>
                                <li><a href="sellerGreeting.php">Seller</a></li>
                                <li><a href="#">Agent</a></li>
                                <li><a href="#">Admin</a></li>
                            </ul>
                        </div>
                        <div class="footer-col">
                            <p>get help</p>
                            <ul>
                                <li><a href="#top">go to top</a></li>
                                <li><a href="#">shipping</a></li>
                                <li><a href="#">returns</a></li>
                                <li><a href="#">order status</a></li>
                                <li><a href="#">payment options</a></li>
                            </ul>
                        </div>
                        <div class="footer-col">
                            <p>shop</p>
                            <ul>
                                <li><a href="catalogue.php?category=<?=$guitar?>">guitars</a></li>
                                <li><a href="catalogue.php?category=<?=$piano?>">pianos</a></li>
                                <li><a href="catalogue.php?category=<?=$keyboard?>">keyboards</a></li>
                                <li><a href="catalogue.php?category=<?=$drums?>">drums</a></li>
                                <li><a href="catalogue.php?category=<?=$wind?>">wind</a></li>
                            </ul>
                        </div>
                        <div class="footer-col">
                            <p>follow us</p>
                            <div class="social-links">
                                <a href="#"><i class="fab fa-facebook-f"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="#"><i class="fab fa-instagram"></i></a>
                                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>          
</html>