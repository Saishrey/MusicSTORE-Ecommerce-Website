<?php 

    require "../private/autoload.php";
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
        <title> UserAccount | MusicSTORE</title>
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
                /* background: url(images/bg_img1.png) no-repeat;
                background-size: cover; */
                font-family: 'Montserrat', sans-serif;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }
            header {
                /* background: #0082e6; */
                /* background: #181818; */
                /* background: rgba(24,24,24, 0.9); */
                background: rgb(24,24,24);
                height: 80px;
                width: 100%;
                border-bottom: 1px solid #1b9bff;
                position: fixed;
                z-index: 3;
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
                /* padding-left: 10px; */
                padding: 30px 10px;
                border-radius: 3px;
                text-decoration: none;
                text-transform: uppercase;
            }
            header  ul > li:hover {
                background: #1b9bff;
                transition: .5s;
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
                    /* background: rgba(24,24,24, 0.6); */
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
            /* User account */
            @keyframes topbar-anim {
                from {
                    background: url(images/large.jpg) no-repeat;
                    background-size: cover;
                }
                to {
                    background: url(images/bg_main.png) no-repeat;
                    background-size: cover;
                }
            }
            main .topbar {
                padding: 80px;
                background: url(images/bg_main.png) no-repeat;
                background-size: cover;
                animation-name: topbar-anim;
                animation-duration: 1s;
                animation-timing-function: linear;
                /* background: #2c3e50; */
                /* background: rgba(24,24,24, 0.9); */
                /* background: rgba(7, 38, 65, 0.8); */
                /* background: linear-gradient(rgba(7, 38, 65, 0.8), rgba(24,24,24, 0.9)); */
                /* background: linear-gradient(rgba(7, 38, 65), rgba(24,24,24)); */
                /* clip-path: ellipse(75% 100% at 50% 0%); */
            }
            main .topbar > ul {
                list-style: none;
                margin: auto;
                padding: 30px 0;
                text-align: center;
                align-items: center;
                width: max-content;
            }
            @keyframes img-anim {
                from {
                    width: 200px;
                    height: 200px;
                }
                to {
                    width: 300px;
                    height: 300px;
                }
            }
            .circular_image {
                width: 300px;
                height: 300px;
                border-radius: 50%;
                overflow: hidden;
                display: inline-block;
                border: 6px solid #3EDBF0;
                animation-name: img-anim;
                animation-duration: 300ms;
                animation-timing-function: ease-out;
            }
            .circular_image a {
                margin:0;
                padding:0;
                border: none;
                outline: none;
            }
            .circular_image:hover {
                /* border: 6px solid #3EDBF0; */
                box-shadow: 0 0 20px 7px #3EDBF0;
                transition: 300ms linear;
                cursor: pointer;
            }
            main .topbar .circular_image img{
                /* clip-path: circle(); */
                width: 100%;
                height: 100%;
            }
            /* main .topbar > ul li img {
                border-radius: 50%;
                width: 40%;
                height: 40%;
            }
            main .topbar > ul li img:hover {
                cursor: pointer;
            } */
            main .topbar > ul li h1 {
                font-size: 40px;
            }
            main .topbar > ul li, a {
                padding: 10px;
                color: white;
                text-decoration: none;
            }
            main .topbar > ul li a:hover {
                color: #1b9bff;
            }

            main .main-body {
                /* background: url(images/bg_img1.png) no-repeat;
                background-size: cover; */
                background: linear-gradient(whitesmoke, #E6E6E6);
                /* padding-bottom: 80px; */
            }
            @keyframes data-anim {
                0% {transform: rotateY(90deg);}
                25% {transform: rotateY(60deg);}
                50% {transform: rotateY(45deg);}
                75% {transform: rotateY(30deg);}
                100% {transform: none;}
            }
            .data {
                display: flex;
                flex-direction: column;
                margin: auto;
                border-radius: 6px;
                position: relative;
                bottom: 80px;
                /* width: 100%; */
                max-width: 1000px;
                background: white;
                z-index: 2;
                box-shadow: 0 7px 20px rgba(50, 50, 93, .2);
                align-items: center;
                /* background-color: #A2DBFA; */
                padding: 80px 0 80px 0;
                animation-name: data-anim;
                animation-duration: 400ms;
                animation-timing-function: linear;
                /* background-color: #373737; */
                /* padding-bottom: 80px; */
                /* background: #181818; */
                /* background: rgba(24,24,24, 0.8); */
            }
            .data:hover {
                box-shadow: 0 10px 30px rgba(50, 50, 93, .2);
                transition: 500ms;
            }
            .data h2 {
                font-size: 3rem;
                margin-bottom: 40px;
                /* color: black; */
                color: rgb(50,50,93);
            }
            .item{
                padding: 20px;
                font-size: 20px;
                width: 80%;
                /* margin: 15px; */
                border: none;
                outline: none;                
                color: rgb(96,108,138);
                /* background-color: #F1F3F4; */
                /* background-color: #B8C1C6; */
            }
            .item .fix-email {
                padding: 5px 2px;
                font-size: 18px;
                max-width: 1000px;
                width: 60%;
                /* margin: 15px; */
                border: none;
                /* border-bottom: 2px solid grey; */
                font-family: 'Montserrat', sans-serif;
                outline: none;
                float: right;
                color: grey;
                /* background-color: #F1F3F4; */
                /* background-color: #B8C1C6; */
                /* background-color: #373737; */
                background: none;
            }
            .form-control {
                padding: 5px 2px;
                font-size: 18px;
                max-width: 1000px;
                width: 60%;
                /* margin: 15px; */
                border: none;
                border-bottom: 2px solid grey;
                font-family: 'Montserrat', sans-serif;
                outline: none;
                float: right;
                color: black;
                /* background-color: #F1F3F4; */
                /* background-color: #B8C1C6; */
                /* background-color: #373737; */
                background: none;
            }
            .data .update {
                text-decoration: none;
                /* color: #4A616B; */
                color: grey;
                margin-top: 20px;
                font-size: 14px;
            }
            .data .update:hover {
                /* color: #1A73E8; */
                color: #1b9bff;
            }
            hr {
                width: 80%;
                margin: 30px;
            }
            .deactivate {
                padding: 12px 30px;
                width: 40%;
                margin-top: 15px;
                /* background-color: #32AEF2; */
                /* background: #181818; */
                background: none;
                color: rgb(50,50,93);
                font-size: 20px;
                font-family: 'Montserrat', sans-serif;
                text-transform: uppercase;
                text-align: center;
                border: 1px solid rgb(50,50,93);
                outline: none;
                border-radius: 6px;
            }
            .deactivate:hover {
                cursor: pointer;
                /* background-color: #195aaf; */
                background: red;
                color:white;
                border: 1px solid red;
                transition: 0.3s;
            }
            .submit-btn {
                padding: 12px 30px;
                width: 40%;
                margin-top: 15px;
                /* background-color: #32AEF2; */
                /* background: #181818; */
                background: none;
                color: rgb(50,50,93);
                font-size: 20px;
                font-family: 'Montserrat', sans-serif;
                text-transform: uppercase;
                border: 1px solid rgb(50,50,93);
                outline: none;
                border-radius: 6px;
            }
            .submit-btn:hover {
                cursor: pointer;
                color: white;
                /* background-color: #195aaf; */
                background: #1b9bff;
                border: 1px solid #1b9bff;
                transition: 0.3s;
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
                /* color: #ffffff; */
                text-decoration: none;
                font-weight: 300;
                padding: 0;
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
                /* line-height: 40px; */
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
            <header class="myheader" id="header">
                <input type="checkbox" id="check">
                <label for="check" class="checkbtn">
                    <i class="fa fa-bars"></i>
                </label>
                <a class="logo" href="index.php">Music<span style="color:#1b9bff;">STORE</span>&trade;</a>
                <ul class="nav-list">
                    <li><a class="active" href="index.php">Home</a></li>
                    <li><a class="active" href="#">Orders</a></li>
                    <li><a class="active" href="#">Cart</a></li>
                    <li><a class="active" href="useraccount.php"><?=$_SESSION['user_name']?></a></li>
                </ul>                
        </header>
            <main id="top">
                <div class="user-info">
                    <div class="topbar">
                        <ul>
                            <li>
                                <div class="circular_image">
                                    <?php
                                        if($_SESSION['img_name'] != null) {
                                    ?>
                                    <a href="update.php" title="Click to change dp"><img src="../private/uploads/<?=$_SESSION['img_name']?>" alt="DP"></a>
                                    <?php
                                        } else {
                                    ?>
                                    <a href="update.php" title="Click to upload dp"><img src="images/user-profile-img.png" alt="DP"></a>
                                    <?php
                                        }
                                    ?>
                                </div>
                            </li>
                            <li><h1><?=$_SESSION['user_name']?></h1></li>
                            <li><h4><?=$_SESSION['email']?></h4></li>
                            <li>
                                <?php
                                    if($_SESSION['contact'] != null) {
                                ?>
                                <h4><?=$_SESSION['contact']?></h4>
                                <?php
                                     }
                                ?>
                            </li>
                            <li><h3><a href="logout.php">Sign out</a><h3></li>
                        </ul>
                    </div>
                    <div class="main-body">
                        <form class="data" method="post" action="update.php">
                            <h2>Account Info</h2>
                            <p style='color:blue; font-size:14px'>Fill only those fields which you want to update.</p>
                            <div class="item">
                                <label for="email">Email:</label>
                                <p class="fix-email" id="email" title="Cannot edit email"><?=$_SESSION['email']?></p>
                            </div>
                            <div class="item">
                                <label for="username">Username:</label>
                                <input type="text" class="form-control" id="username" name="user_name" placeholder="Username" value="<?=$_SESSION['user_name']?>" maxlength="20" title="Username">
                            </div>
                            <div class="item">
                                <label for="contact">Contact:</label>
                                <input type="text" name="contact" id="contact" class="form-control" placeholder="Contact" value="<?=$_SESSION['contact']?>" maxlength="10" title="Contact">
                            </div>
                            <div class="item">
                                <label for="addres">Address:</label>
                                <input type="text" name="address" id="address" class="form-control" placeholder="Address" value="<?=$_SESSION['address']?>" maxlength="250" title="Address">
                            </div>
                            <div class="item">
                                <label for="pincode">PIN Code:</label>
                                <input type="text" name="pin_code" id="pincode" class="form-control" placeholder="PIN Code" value="<?=$_SESSION['pin_code']?>" maxlength="6" title="Pin code">
                            </div>
                            
                            <input type="submit" class="submit-btn" value="Update" name="update">
                            <hr>
                            <a href="deactivate.php" class="deactivate">Deactivate Account</a>
                        </form>
                    </div>
                </div>
        </main>
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="footer-col">
                        <p>Account</p>
                        <ul>
                            <li><a href="useraccount.php">Customer</a></li>
                            <li><a href="sellerGreeting.php">Seller</a></li>
                            <li><a href="#">Agent</a></li>
                            <li><a href="#">Admin</a></li>
                        </ul>
                    </div>
                    <div class="footer-col">
                        <p>get help</p>
                        <ul>
                            <li><a href="#top">Go to top</a></li>
                            <li><a href="#">shipping</a></li>
                            <li><a href="#">returns</a></li>
                            <li><a href="#">order status</a></li>
                            <li><a href="#">payment options</a></li>
                        </ul>
                    </div>
                    <div class="footer-col">
                        <p>shop</p>
                        <ul>
                            <li><a href="#">guitars</a></li>
                            <li><a href="#">keyboards</a></li>
                            <li><a href="#">pianos</a></li>
                            <li><a href="#">flutes</a></li>
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
    </body>
</html>