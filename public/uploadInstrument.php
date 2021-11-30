<?php 

    require "../private/autoload.php";

    $inst_name = "";
    $category = "";

    $error_stmnt = "";
    $error = False;
    $error_num = 0;

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        //something was posted
        // to check if model name matches pattern
        $inst_name = trim($_POST['inst_name']);
        if(strlen($inst_name) != 0 && !preg_match("/^[a-zA-Z0-9 -.,]+$/", $inst_name) && !$error) {
            $error_stmnt .= "<p style='color:#F78812; font-size:14px'>";
            $error_stmnt .= "<i class='fa fa-exclamation-circle'></i> ";
            $error_stmnt .= "Model name can only use letters, spaces, numbers and - . , ";
            $error_stmnt .= "</p>";
            $error = True;
        }
        $inst_name = esc($inst_name);

        $price_str = trim($_POST['inst_price']);
        if(strlen($price_str) != 0 && !preg_match("/^[0-9]+$/", $price_str) && !$error) {
            $error_stmnt .= "<p style='color:#F78812; font-size:14px'>";
            $error_stmnt .= "<i class='fa fa-exclamation-circle'></i> ";
            $error_stmnt .= "Price can only have digits.";
            $error_stmnt .= "</p>";
            $error_num = 1;
            $error = True;
        }
        $price = intval($price_str);

       $category = $_POST['category'];

        if(!$error) {

            //save to database
            $arr['inst_name'] = $inst_name;
            $arr['s_id'] = $_SESSION['seller_id'];
            $arr['category'] = $category;
            $arr['inst_id'] = "INST".get_random_string(20);
            $arr['price'] = $price;
            $query = "insert into instrument (inst_id,s_id,inst_name,price,category) values (:inst_id,:s_id,:inst_name,:price,:category);";
            $stmnt = $con->prepare($query);
            $stmnt->execute($arr);

            echo "<script>
                  alert('Instrument uploaded successfully.');
                  window.location.replace('instruments.php');
                  </script>";        
            
        }
        
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
        <title> SellerAccount | MusicSTORE</title>
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
                /* background: url(images/selleraccount_bg.png) no-repeat; */
                /* background-size: cover; */
                background: whitesmoke;
                font-family: 'Montserrat', sans-serif;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }
            header {
                /* background: #0082e6; */
                /* background: #181818; */
                /* background: rgba(24,24,24, 0.9); */
                background: rgba(24,24,24, 0.97);
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
            /* Seller account */
            /* @keyframes topbar-anim {
                from {
                    background: url(images/large.jpg) no-repeat;
                    background-size: cover;
                }
                to {
                    background: url(images/bg_main.png) no-repeat;
                    background-size: cover;
                }
            } */
            main {
                padding: 80px 0 80px 0;
                /* background: url(images/user_account);
                background-size: cover; */
            }
            main .topbar {
                height: 500px;
                min-width: 1260px;
                /* padding: 80px; */
                /* background: url(images/bg_main.png) no-repeat;
                background-size: cover; */
                /* background: rgb(5,29,54); */
                /* background: linear-gradient(#5D1451, rgb(5,29,54)); */
                /* clip-path: ellipse(75% 100% at 50% 0%); */
                /* animation-name: topbar-anim;
                animation-duration: 400ms;
                animation-timing-function: linear; */
            }
            main .topbar-bg {
                padding: 80px;
                height: 300px;
                /* background: linear-gradient(#5D1451, rgb(5,29,54)); */
                background: url(images/user_account.jpg);
                background-size: cover;
                background-color: rgb(5,29,54);
            }
            ul {
                list-style: none;
            }
            main .topbar .topbar-container {
                height: 200px;
                display: flex;
                flex-direction: row;
                width: 100%;
                border: 2px solid #1b9bff;
                border-top: none;
                border-radius: 0 0 20px 20px;
                box-shadow: 0 7px 20px rgba(50, 50, 93, .2);
                background: white;
            }
            .img-container {
                width: 25%;
                min-width: 470px;
            }
            .outer-ul {
                padding: 20px 0 20px 0;
                display: flex;
                flex-direction: column;
                /* flex-wrap: wrap; */
                /* margin: auto; */
                /* padding: 30px 0; */
                /* text-align: center; */
                width: 25%;
                min-width: 470px;
            }
            .outer-ul li {
                padding: 5px 0 5px 0;
            }
            @keyframes ul-anim {
                from {
                    left: 300px;
                }
                to {
                    left: 0;
                }
            }
            .btn-container {
                /* margin-top: 30px; */
                width: 50%;
            }
            .sign-out {
                text-align: right;
                margin: 80px 100px 50px 0;
                /* margin-left: auto; */
                /* margin-right: 0; */
            }
            .sign-out a {
                padding: 10px 30px;
                background: rgb(5,29,54);
                border-radius: 6px;
                color: white;
                text-decoration: none;
                text-transform: uppercase;
            }
            .sign-out a:hover {
                background: #1b9bff;
                transition: 250ms;
            }
            .circular_image {
                margin-left: 100px;
                margin-right: 20px;
                width: 300px;
                height: 300px;
                border-radius: 50%;
                overflow: hidden;
                display: inline-block;
                border: 6px solid #3EDBF0;
                position: relative;
                bottom: 150px;
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
            main .main-body {
                /* background: linear-gradient(whitesmoke, #E6E6E6); */
                padding: 80px 20px 0 20px;
                display: flex;
                flex-direction: column;
                /* padding-bottom: 80px; */
            }
            .data {
                width: 70%;
                background: white;
                min-width: 625px;
                margin: auto;
                z-index: 2;
                border-radius: 6px;
                box-shadow: 0 7px 20px rgba(50, 50, 93, .2);
                padding-bottom: 80px;
            }
            .top-menu {
                width: 100%;
                border-radius: 6px 6px 0 0;
                background: rgb(5,29,54);
                padding: 20px 40px;
            }
            .data .top-menu ul{
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
                width: 100%;
                text-align: center;
            }
            .top-menu ul li {
                /* padding: 20px 30px; */
                /* margin: 8px; */
                width: 25%;
            }
            .top-menu ul li a {
                text-decoration: none;
                padding: 10px 30px;
                color: #969BA1;
            }
            .top-menu ul li .active {
                color: #1b9bff;
                border-bottom: 2px solid white;
            }
            .top-menu ul li a:hover {
                color: #1b9bff;
                border-bottom: 2px solid white;
            }
            .data form {
                display: flex;
                flex-direction: column;
                /* margin: 0 40px 0 40px; */
                border-radius: 6px;
                /* position: relative;
                bottom: 80px; */
                /* height: 860px; */
                /* width: 100%; */
                /* max-width: 1000px; */
                /* min-width: 800px; */
                align-items: center;
                /* background-color: #A2DBFA; */
                padding: 80px 0 80px 0;
                /* background-color: #373737; */
                /* padding-bottom: 80px; */
                /* background: #181818; */
                /* background: rgba(24,24,24, 0.8); */
            }
            .data form h2 {
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
            form hr {
                width: 80%;
                margin: 30px;
            }
            .submit-btn {
                padding: 10px 40px;
                width: 40%;
                margin-top: 15px;
                /* background-color: #32AEF2; */
                /* background: #181818; */
                background: #3DB026;
                border: none;
                color: white;
                font-size: 20px;
                font-family: 'Montserrat', sans-serif;
                text-transform: uppercase;
                outline: none;
                border-radius: 6px;
            }
            .submit-btn:hover {
                cursor: pointer;
                color: white;
                /* background-color: #195aaf; */
                background: #369b22;
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
            <header class="myheader" id="header">
                <input type="checkbox" id="check">
                <label for="check" class="checkbtn">
                    <i class="fa fa-bars"></i>
                </label>
                <a class="logo" href="index.php">Music<span style="color:#1b9bff;">STORE</span>&trade;<sub> Seller</sub></a>
                <ul class="nav-list">
                    <li><a class="active" href="index.php">Home</a></li>
                    <li><a class="active" href="instruments.php"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a></li>
                </ul>                
        </header>
            <main id="top">
                <div class="user-info">
                    <div class="topbar">
                        <div class="topbar-bg">
                        </div>
                        <div class="topbar-container">
                            <div class="img-container">
                                <div class="circular_image">
                                    <?php
                                        if($_SESSION['seller_dp'] != null) {
                                    ?>
                                    <a href="updateseller.php" title="Click to change dp"><img src="../private/uploads/<?=$_SESSION['seller_dp']?>" alt="DP"></a>
                                    <?php
                                        } else {
                                    ?>
                                    <a href="updateseller.php" title="Click to upload dp"><img src="images/seller-dp.png" alt="DP"></a>
                                    <?php
                                        }
                                    ?>
                                </div>
                            </div>
                            <ul class="outer-ul">
                                <li><h1><?=$_SESSION['company_name']?></h1></li>
                                <li><h4><?=$_SESSION['email']?></h4></li>
                                <li><h4><?=$_SESSION['seller_contact']?></h4></li>
                            </ul>
                            <div class="btn-container">
                                <h3 class="sign-out" ><a href="logout.php">Sign out</a><h3>
                            </div>
                        </div>
                    </div>
                    <div class="main-body">
                        <div class="data">
                            <div class="top-menu">
                                <div class="ul-div">
                                    <ul>
                                        <li><a href="selleraccount.php">Account</a></li>
                                        <li><a class="active" href="instruments.php">Instruments</a></li>
                                        <li><a href="#">Sales</a></li>
                                        <li><a href="#">Reviews</a></li>
                                    </ul>
                                </div>
                            </div>
                                <form action="" method="post">
                                    <h2>Upload Instrument</h2>
                                    <div class="item">
                                        <label for="brand">Brand:</label>
                                        <p class="fix-email" id="brand" title="Cannot edit brand"><?=$_SESSION['company_name']?></p>
                                    </div>
                                    <div class="item">
                                        <label for="modelname">Model Name:</label>
                                        <input type="text" class="form-control" id="modelname" name="inst_name" placeholder="Model Name" maxlength="250" required="required">
                                        <?php
                                            if(isset($error_stmnt) && $error_num == 0 && $error_stmnt != "") {
                                                echo $error_stmnt;
                                            }
                                        ?>
                                    </div>
                                    <div class="item">
                                        <label for="price">Price:</label>
                                        <input type="text" class="form-control" id="price" name="inst_price" placeholder="Price" maxlength="8" minlength="2" required="required">
                                        <?php
                                            if(isset($error_stmnt) && $error_num == 1 && $error_stmnt != "") {
                                                echo $error_stmnt;
                                            }
                                        ?>
                                    </div>
                                    <div class="item">
                                        <label for="category">Category:</label>
                                        <select name="category" id="category" class="form-control">
                                            <option value="guitar">Guitar</option>
                                            <option value="piano">Piano</option>
                                            <option value="keyboard">Keyboard</option>
                                            <option value="drums">Drums & Percussions</option>
                                            <option value="wind">Wind instruments</option>
                                        </select>
                                    </div>
                                    <hr>
                                    <input type="submit" class="submit-btn" value="Upload" name="upload-inst">
                                </form>

                        </div>
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
                            <?php 
                                if($_SESSION['is_seller'] == 0) {
                            ?>
                            <li><a href="sellerGreeting.php">Seller</a></li>
                            <?php
                                } else {
                            ?>
                                <li><a href="selleraccount.php">Seller</a></li>
                            <?php
                                }
                            ?>
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