<?php 

    require "../private/autoload.php";

    $user_data = check_login($con);

    
    $user_name = "";
    if(isset($_SESSION['user_name'])) {
        $user_name = $_SESSION['user_name'];
    }

    $is_seller = "";
    if(isset($_SESSION['is_seller'])) {
        $is_seller = $_SESSION['is_seller'];
    }


    //fetch instruments from database
    $search = "";

    $category = "";
    if(isset($_GET['category'])) {
        $category = $_GET['category'];
        $arr['category'] = strtolower($category);
        $query = "select * from instrument where category=:category;";
        $stmnt = $con->prepare($query);
        $check = $stmnt->execute($arr);

        if($check) {
            $inst_data = $stmnt->fetchAll(PDO::FETCH_OBJ);  //FETCH_ASSOC for array
        }

    } else if(isset($_GET['search'])) {
        $search = strtolower($_GET['search']);
        $search_db = "%".$search."%";
        $arr['search'] = $search_db;
        $query = "select * from instrument where inst_name like :search or category like :search;";
        $stmnt = $con->prepare($query);
        $check = $stmnt->execute($arr);

        if($check) {
            $inst_data = $stmnt->fetchAll(PDO::FETCH_OBJ);  //FETCH_ASSOC for array
        }
        
    } else {
        $query = "select * from instrument order by rand();";
        $stmnt = $con->prepare($query);
        $check = $stmnt->execute();

        if($check) {
            $inst_data = $stmnt->fetchAll(PDO::FETCH_OBJ);  //FETCH_ASSOC for array
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
        <link rel="stylesheet" href="css/animate.css">
        <script src="js/wow.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css">
        <!-- <link rel="stylesheet" href="styling.css"> -->
        <title>MusicSTORE | E-COMMERCE WEBSITE</title>
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
                z-index: 3;
                /* opacity: 0.7; */
                height: 80px;
                width: 100%;
                border-bottom: 1px solid #1b9bff;
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
            header ul ul > li:hover{
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
            /* ////////////////////////////////////////////////////////////////////////////////////////////////////////// */
            main {
                margin-top: 80px;
            }
            main .top-section {
                background: url(images/slider-bg_blue.jpg);
                background-color: rgb(152,228,254);
                background-size: cover;
                background-position: 10% 10%;
            }
            .top-section .box {
                width: 50%;
                padding: 200px 0 200px 0;
            }
            .top-section .detail-box {
                margin: auto;
                width:60%;
                padding: 40px;
                text-align: left;
            }
            .detail-box h1 span {
                font-size: 70px;
                color: #DF711B;
            }
            .detail-box h1 {
                font-size: 50px;
                color: #0F2C67;
            }
            .btn-box {
                margin-top: 20px;
            }
            .btn-box a {
                text-decoration: none;
                padding: 10px 30px;
                background: #3DB026;
                border-radius: 3px;
                color: white;
            }
            .btn-box a:hover {
                background: #369b22;
                transition: 250ms;
            }
            .why-shop {
                padding: 100px;
                background: whitesmoke;
                min-width: 1425px;
            }
            .why-shop h1 {
                color: #0F2C67;
                font-size: 40px;
                text-align:center;
            }
            .why-shop-container  {
                width: 60%;
                margin: auto;
                margin-top: 40px;
                display: flex;
                flex-direction: row;
            }
            .why-shop-box {
                background: #2C394B;
                border-radius: 6px;
                text-align: center;
                color: white;
                padding: 40px 50px;
                width: 28%;
                margin: auto;
            }
            .why-shop-box .img-box {
                margin-bottom: 15px;
            }
            .why-shop-box .img-box svg {
                width: 55px;
                height: auto;
                fill: #ffffff;
            }
            .why-shop-box h2 {
                font-size: 24px;
            }

            .quote-div {
                background: rgb(152,228,254);
            }
            .quote-div .img-container {
                width: 70%;
                background: url(images/arrival-bg_blue.jpg);
                background-size: cover;
                display:flex;
                flex-direction: row;
                margin: auto;
                height: 500px;
            }
            .quote-container {
                width: 50%;
                padding: 80px;
                margin: auto;
            }
            .quote h1 {
                color: #0F2C67;
                font-size: 40px;
                margin-bottom: 10px;
            }
            .quote h2 {
                color: #2C2E43;
                font-size: 24px;
                font-weight: 100;
                margin-bottom: 10px;

            }
            .quote p {
                font-style: italic;
                margin-bottom: 10px;
            }
            .category-sbmt-btn {
                background: none;
                width: 100%;
                text-align: left;
                padding: 10px;
                border: none;
                font-size: 17px;
                color: white;
                font-family: 'Montserrat', sans-serif;
                text-transform: uppercase;
            }
            .category-sbmt-btn:hover {
                cursor: pointer;
            }



            /* footer //////////////////////////////////////////////////////////////////////////////////////*/
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
            .footer-col ul li a , .footer-col ul li .category-sbmt-btn {
                font-size: 13px;
                text-transform: capitalize;
                color: #ffffff;
                text-decoration: none;
                font-weight: 300;
                color: #bbbbbb;
                display: block;
                margin: 0;
                padding: 0;
                transition: all 0.3s ease;
            }
            .footer-col ul li a:hover, .footer-col ul li .category-sbmt-btn:hover{
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
                <a class="logo" href="index.php">Music<span style="color:#1b9bff;">STORE</span>&trade;</a>
                <ul class="nav-list">
                    <li><a class="active" href="index.php">Home</a></li>
                    <li>
                        <a  href="#">Categories</a>
                        <ul class="sub-list-account">
                            <li>
                                <form action="catalogue.php" method="get">
                                    <input type="text" class="hidden-input" name="category" value="Guitar">
                                    <input type="submit" class="category-sbmt-btn" value="Guitar">
                                </form>
                            </li>
                            <li>
                                <form action="catalogue.php" method="get">
                                    <input type="text" class="hidden-input" name="category" value="Piano">
                                    <input type="submit" class="category-sbmt-btn" value="Piano">
                                </form>
                            </li>
                            <li>
                                <form action="catalogue.php" method="get">
                                    <input type="text" class="hidden-input" name="category" value="Keyboard">
                                    <input type="submit" class="category-sbmt-btn" value="Keyboard">
                                </form>
                            </li>
                            <li>
                                <form action="catalogue.php" method="get">
                                    <input type="text" class="hidden-input" name="category" value="Drums and Percussions">
                                    <input type="submit" class="category-sbmt-btn" value="Drums">
                                </form>
                            </li>
                            <li>
                                <form action="catalogue.php" method="get">
                                    <input type="text" class="hidden-input" name="category" value="Wind instruments">
                                    <input type="submit" class="category-sbmt-btn" value="Wind">
                                </form>
                            </li>
                            <li>
                                <a href="catalogue.php" class="category-sbmt-btn">All Categories</a>
                            </li>
                        </ul>
                    </li>
                    <li><a  href="aboutUs.php">About us</a></li>
                    <li><a  href="#">Contact</a></li>
                    <?php
                        if($user_name != "") {
                    ?>
                    <li> 
                        <a  href="useraccount.php"><?=$_SESSION['user_name']?></a>
                        <ul class="sub-list-account">
                            <li><a href="useraccount.php">My Profile</a></li>
                            <?php 
                                if($is_seller == 1) {
                            ?>
                            <li><a href="selleraccount.php">My Seller Profile</a></li>
                            <?php
                                }
                            ?>
                            <li><a href="useraccount.php?orders=true">Orders</a></li>
                            <li><a href="cart.php">Cart</a></li>
                            <hr>
                            <li><a href="logout.php">Sign out</a></li>
                        </ul>
                    </li>
                    <?php
                        } else {
                    ?>
                    <li class="submenu">
                        <a  href="login.php">Sign in</a>
                        <!-- <a onclick="window.open(document.URL, '_popup', 'location=yes,height=570,width=520,scrollbars=yes,status=yes');">Open New Window</a> -->
                    <?php
                        }
                    ?>
                </ul>                
            </header>
            <main id="top">
                <style>
                    main .heading {
                        text-align: center;
                        padding: 80px;
                        font-size: 2.5rem;
                    }
                    main .products-div > h2 {
                        text-align: center;
                        padding: 20px;
                    }
                    main .product-box {
                        background: rgb(239,243,246);
                        height: auto;
                        min-width: 1425px;
                        padding: 100px;
                    }
                    .product-box h1 {
                        width: 80%;
                        margin: auto;
                        text-align: center;
                        font-weight: 100;
                        font-size: 48px;
                        color: black;
                    }
                    .product-box .search-box-container {
                        width: 80%;                  
                        margin: auto;
                        text-align: center;
                        padding: 40px;
                    }
                    .search-box-container .search-box from {
                        font-family: 'Montserrat', sans-serif;
                        color: black;
                    }
                    .search-box-container .form-control {
                        background: none;
                        font-size: 22px;
                        color: black;
                        min-height: 55px;
                        padding: 5px 10px;
                        width: 40%;
                        border-radius: 6px 6px 0 0;
                        border: none;
                        border-bottom: 2px solid grey;
                        outline: none;
                        margin: 0 15px 0 15px;
                    }
                    .search-box-container .sbmt-btn {
                        padding: 10px 20px;
                        margin-bottom: 0;
                        font-size: 22px;
                        text-align: center;
                        background: #1b9bff;
                        font-family: 'Montserrat', sans-serif;
                        color: white;
                        min-height: 55px;
                        border-bottom: 1px solid #1b9bff;
                        border: none;
                        border-radius: 4px;
                    }
                    .sbmt-btn:hover {
                        cursor: pointer;
                        background: #337ab7;
                        transition: 250ms;
                    }
                    .product-box .view-all-inst {
                        width: 80%;
                        padding-top: 40px;
                        margin: auto;
                        text-align: center;
                    }
                    .view-all-inst a {
                        font-size: 22px;
                        text-decoration: none;
                        color: white;
                    }
                    .view-all-inst a:hover {
                        color: #1b9bff;
                        transition: 500ms linear;
                    }
                    .products-div {
                        width: 100%;
                        display: flex;
                        flex-direction: column;
                    }
                    .products-div .product-container {
                        width: 80%;
                        margin: auto;
                        margin-top: 30px;
                        margin-bottom: 30px;
                        display: flex;
                        flex-direction: row;
                    }
                    .product-container .main-product {
                        width: 350px;
                        margin: auto;
                        margin-top: 10px;
                        margin-bottom: 10px;
                        text-transform: capitalize;
                        /* background: #141E61; */
                        background-color: rgb(239,243,246);
                        /* background: #3D2C8D; */
                        padding: 20px 30px;
                        border-radius: 6px;
                        box-shadow: white -10px -10px 20px 5px, rgb(24, 24, 24, 0.2) 10px 10px 20px 5px ;
                    }
                    .main-product {
                        text-decoration: none;
                    }
                    .main-product h1 {
                        font-size: 28px;
                    }
                    .main-product:hover {
                        background: #B8E4F0;
                        transition: 250ms linear;
                    }
                    .main-product h1, .main-product h2 {
                        color: black;
                        width: 100%;
                        /* color: #150E56; */
                        text-align: left;
                        padding: 10px 0 10px 0;
                    }
                    .main-product h4 {
                        color: grey;
                        text-align: right;
                        width: 100%;
                        font-size: 14px;
                        padding: 10px 0 10px 0;
                    }
                    .inst-img-container {
                        width: 100%;
                        /* min-width: 470px; */
                    }
                    .square-img {
                        /* margin-left: 100px;
                        margin-right: 20px; */
                        width: 100%;
                        height: 375px;
                        overflow: hidden;
                        display: inline-block;
                        border-radius: 6px;
                    }
                    .square-img img{
                        /* clip-path: circle(); */
                        width: 100%;
                        height: 100%;
                    }
                    .price-and-buy {
                        display: flex;
                        flex-direction: row;
                    }
                    .price-and-buy .sub-div {
                        width: 100%;
                        display: flex;
                        flex-direction: row;
                    }
                    .hidden-input {
                        display: none;
                    }
                    .sub-div h2, h3 {
                        text-align: center;
                        height: 60px;
                        width: 50%;
                        padding: 10px 0 10px 0;

                    }
                    .sub-div h3 {
                        color: grey;
                    }
                    /* .sub-div form {
                        width: 100%;
                        text-align: right;
                    }
                    .sub-div form .sbmt-btn {
                        text-decoration: none;
                        width: 100%;
                        padding: 15px;
                        background: #F3950D;
                        border: none;
                        font-family: 'Montserrat', sans-serif;
                        color: white;
                        font-size: 18px;
                        text-align:center;
                        margin-top: 25px;
                        border-radius: 3px;
                    }
                    .sub-div form .sbmt-btn:hover {
                        background: rgb(187,103,54);
                        transition: 250ms linear;
                    } */

                   

                </style>
                <div class="product-box">
                    <h1>Find your next instrument here.</h1>
                    <div class="search-box-container">
                        <form method="get" action="catalogue.php">
                            <input type="search" name="search" class="form-control" placeholder="Search your favourites">
                            <input type="submit" value="Search" class="sbmt-btn">
                        </form>
                    </div>
                    <div class="heading">
                        <?php
                            if($category != "") {
                        ?>
                        <h1><span style="color:#1b9bff; text-transform: capitalize;"><?=$category?></span></h1>
                        <?php 
                            } else if($search != "") {
                        ?>
                        <h1>You searched for <span style="color:#1b9bff; text-transform: capitalize;"><?=$search?></span></h1>
                        <?php
                            } else {
                        ?>
                        <h1>All <span style="color:#1b9bff; text-transform: uppercase;">Instruments</span></h1>
                        <?php
                            }
                        ?>
                    </div>
                    <hr>
                    <div class="products-div">
                        <?php
                            if(count($inst_data) == 0) {
                        ?>
                        <h2 style="color: grey;">No results found &#128549;.</h2>
                        <?php
                            } else { 
                                $i = 0;
                                $max = count($inst_data);

                                while($i < $max) {
                        ?>
                        <div class="product-container">
                        
                        <?php 
                            $count = 0;
                            while($count < 3 && $i < $max) {
                        ?>

                        <a class="main-product" href="productPage.php?inst_id=<?=$inst_data[$i]->inst_id?>&category=<?=$inst_data[$i]->category?>" target="_blank">
                            <div class="prod-info" class="wow">
                               
                                <h1><?=$inst_data[$i]->inst_name?></h1>
                                <h4><?=$inst_data[$i]->brand_name?></h4>
                                <div class="inst-img-container">
                                    <div class="square-img" >
                                        <img src="../private/uploads/<?=$inst_data[$i]->inst_img?>" alt="Image">
                                    </div>
                                </div>
                                <div class="price-and-buy">
                                    <div class="sub-div">
                                        <h2>&#8377; <?=$inst_data[$i]->price?> </h2>
                                        <?php
                                            $og_price = $inst_data[$i]->price + (0.2*$inst_data[$i]->price);
                                        ?>
                                        <h3>&#8377;<s><?=$og_price?></s></h3>
                                    </div>
                                </div>
                                
                                
                            </div>
                        <?php
                            $count++;
                            $i++;
                            }
                        ?>
                        </div>
                        </a>
                        <?php
                            }
                        }
                        ?>
                    </div>
                    <!-- <div class="view-all-inst">
                        <a  href="">View all Instruments</a>
                    </div> -->
                </div>
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
                                <?php 
                                    if($is_seller == 0) {
                                ?>
                                <li><a href="sellerGreeting.php">Seller</a></li>
                                <?php
                                    } else {
                                ?>
                                    <li><a href="selleraccount.php">Seller</a></li>
                                <?php
                                    }
                                ?>
                                <?php
                                    if(!isset($_SESSION['user_id'])) {
                                ?>
                                    <li><a href="login.php?agentLogin=true">Agent</a></li>
                                <?php
                                    }
                                ?>
                              
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
                            <li>
                                <form action="catalogue.php" method="get">
                                    <input type="text" class="hidden-input" name="category" value="Guitar">
                                    <input type="submit" class="category-sbmt-btn" value="Guitar">
                                </form>
                            </li>
                            <li>
                                <form action="catalogue.php" method="get">
                                    <input type="text" class="hidden-input" name="category" value="Piano">
                                    <input type="submit" class="category-sbmt-btn" value="Piano">
                                </form>
                            </li>
                            <li>
                                <form action="catalogue.php" method="get">
                                    <input type="text" class="hidden-input" name="category" value="Keyboard">
                                    <input type="submit" class="category-sbmt-btn" value="Keyboard">
                                </form>
                            </li>
                            <li>
                                <form action="catalogue.php" method="get">
                                    <input type="text" class="hidden-input" name="category" value="Drums and Percussions">
                                    <input type="submit" class="category-sbmt-btn" value="Drums">
                                </form>
                            </li>
                            <li>
                                <form action="catalogue.php" method="get">
                                    <input type="text" class="hidden-input" name="category" value="Wind instruments">
                                    <input type="submit" class="category-sbmt-btn" value="Wind">
                                </form>
                            </li>
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
    <!-- jQery -->
    <script src="js/jquery-3.4.1.min.js"></script>
      <!-- popper js -->
      <script src="js/popper.min.js"></script>
      <!-- bootstrap js -->
      <script src="js/bootstrap.js"></script>
      <!-- custom js -->
      <script src="js/custom.js"></script>
</html>