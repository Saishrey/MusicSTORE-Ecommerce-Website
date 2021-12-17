<?php 

    require "../private/autoload.php";

    $user_data = check_login($con);

    if(isset($_SESSION['email']) && $_SESSION['email'] == "dbmsbrdrs@gmail.com") {
        header("Location: adminPage.php");
        die;
    }

    $user_name = "";
    if(isset($_SESSION['user_name'])) {
        $user_name = $_SESSION['user_name'];
    }

    $is_seller = "";
    if(isset($_SESSION['is_seller'])) {
        $is_seller = $_SESSION['is_seller'];
    }

    //fetch instruments from database

    $query = "select * from instrument order by rand() limit 6";
    $stmnt = $con->prepare($query);
    $check = $stmnt->execute();

    if($check) {
        $inst_data = $stmnt->fetchAll(PDO::FETCH_OBJ);  //FETCH_ASSOC for array
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
                padding: 200px 0 250px 0;
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
                        <a href="catalogue.php">Categories</a>
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
               <div class="top-section" >
                   <div class="box">
                        <div class="detail-box">
                            <h1><span>Sale 20% Off</span>
                            <br>
                            On all instruments</h1>
                            <div class="btn-box">
                                <a href="catalogue.php" class="btn1">Shop Now</a>   
                            </div>   
                        </div>
                   </div> 
               </div>
                <style>
                    main .product-box {
                        background: url(images/bg_main.png);
                        background-size: cover; 
                        background-attachment: fixed;
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
                        color: white;
                    }
                    .product-box .search-box-container {
                        width: 80%;                  
                        margin: auto;
                        text-align: center;
                        padding: 40px;
                    }
                    .search-box-container .search-box from {
                        font-family: 'Montserrat', sans-serif;
                    }
                    .search-box-container .form-control {
                        background: none;
                        font-size: 22px;
                        color: white;
                        min-height: 55px;
                        width: 40%;
                        border: none;
                        border-bottom: 1px solid white;
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
                        background: #141E61;
                        /* background: #3D2C8D; */
                        padding: 20px 30px;
                        border-radius: 6px;
                        border-bottom: 5px solid #3EDBF0;
                    }
                    .main-product {
                        text-decoration: none;
                    }
                    .main-product h1 {
                        font-size: 28px;
                    }
                    .main-product:hover {
                        /* background: #344CB7; */
                        /* cursor: pointer; */
                        box-shadow: 0 0 20px 7px #3EDBF0;
                        transition: 200ms ease-out;
                    }
                    .main-product h1, .main-product h2 {
                        color: white;
                        width: 100%;
                        /* color: #150E56; */
                        text-align: left;
                        padding: 10px 0 10px 0;
                    }
                    .main-product h4 {
                        color: #D3E4CD;
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
                        color: #D3E4CD;
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
                        <form action="catalogue.php" method="get">
                            <input type="search" name="search" class="form-control" placeholder="Search your favourites">
                            <input type="submit" value="Search" class="sbmt-btn" title="Click to buy">
                        </form>
                    </div>
                    <div class="products-div">
                        <?php
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
                        ?>
                    </div>
                    <div class="view-all-inst">
                        <a  href="catalogue.php">View all Instruments</a>
                    </div>
                </div>
                <div class="quote-div" id="quote-div">
                    <div class="img-container">
                        <div class="quote-container">
                        </div>
                        <div class="quote-container">
                            <div class=quote>
                                <h1>#Quote</h1>
                                <h2>"One good thing about music, when it hits you, you feel no pain."</h2>
                                <div class="btn-box">
                                    <a href="catalogue.php" class="btn1">Shop Now</a>        
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="why-shop">
                    <h1>Why shop with us</h1>
                    <div class="why-shop-container" >
                        <div class="why-shop-box">
                            <div class="img-box">
                                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                                    <g>
                                        <g>
                                            <path d="M476.158,231.363l-13.259-53.035c3.625-0.77,6.345-3.986,6.345-7.839v-8.551c0-18.566-15.105-33.67-33.67-33.67h-60.392
                                                V110.63c0-9.136-7.432-16.568-16.568-16.568H50.772c-9.136,0-16.568,7.432-16.568,16.568V256c0,4.427,3.589,8.017,8.017,8.017
                                                c4.427,0,8.017-3.589,8.017-8.017V110.63c0-0.295,0.239-0.534,0.534-0.534h307.841c0.295,0,0.534,0.239,0.534,0.534v145.372
                                                c0,4.427,3.589,8.017,8.017,8.017c4.427,0,8.017-3.589,8.017-8.017v-9.088h94.569c0.008,0,0.014,0.002,0.021,0.002
                                                c0.008,0,0.015-0.001,0.022-0.001c11.637,0.008,21.518,7.646,24.912,18.171h-24.928c-4.427,0-8.017,3.589-8.017,8.017v17.102
                                                c0,13.851,11.268,25.119,25.119,25.119h9.086v35.273h-20.962c-6.886-19.883-25.787-34.205-47.982-34.205
                                                s-41.097,14.322-47.982,34.205h-3.86v-60.393c0-4.427-3.589-8.017-8.017-8.017c-4.427,0-8.017,3.589-8.017,8.017v60.391H192.817
                                                c-6.886-19.883-25.787-34.205-47.982-34.205s-41.097,14.322-47.982,34.205H50.772c-0.295,0-0.534-0.239-0.534-0.534v-17.637
                                                h34.739c4.427,0,8.017-3.589,8.017-8.017s-3.589-8.017-8.017-8.017H8.017c-4.427,0-8.017,3.589-8.017,8.017
                                                s3.589,8.017,8.017,8.017h26.188v17.637c0,9.136,7.432,16.568,16.568,16.568h43.304c-0.002,0.178-0.014,0.355-0.014,0.534
                                                c0,27.996,22.777,50.772,50.772,50.772s50.772-22.776,50.772-50.772c0-0.18-0.012-0.356-0.014-0.534h180.67
                                                c-0.002,0.178-0.014,0.355-0.014,0.534c0,27.996,22.777,50.772,50.772,50.772c27.995,0,50.772-22.776,50.772-50.772
                                                c0-0.18-0.012-0.356-0.014-0.534h26.203c4.427,0,8.017-3.589,8.017-8.017v-85.511C512,251.989,496.423,234.448,476.158,231.363z
                                                M375.182,144.301h60.392c9.725,0,17.637,7.912,17.637,17.637v0.534h-78.029V144.301z M375.182,230.881v-52.376h71.235
                                                l13.094,52.376H375.182z M144.835,401.904c-19.155,0-34.739-15.583-34.739-34.739s15.584-34.739,34.739-34.739
                                                c19.155,0,34.739,15.583,34.739,34.739S163.99,401.904,144.835,401.904z M427.023,401.904c-19.155,0-34.739-15.583-34.739-34.739
                                                s15.584-34.739,34.739-34.739c19.155,0,34.739,15.583,34.739,34.739S446.178,401.904,427.023,401.904z M495.967,299.29h-9.086
                                                c-5.01,0-9.086-4.076-9.086-9.086v-9.086h18.171V299.29z" />
                                        </g>
                                    </g>
                                    <g>
                                        <g>
                                            <path d="M144.835,350.597c-9.136,0-16.568,7.432-16.568,16.568c0,9.136,7.432,16.568,16.568,16.568
                                                c9.136,0,16.568-7.432,16.568-16.568C161.403,358.029,153.971,350.597,144.835,350.597z" />
                                        </g>
                                    </g>
                                    <g>
                                        <g>
                                            <path d="M427.023,350.597c-9.136,0-16.568,7.432-16.568,16.568c0,9.136,7.432,16.568,16.568,16.568
                                                c9.136,0,16.568-7.432,16.568-16.568C443.591,358.029,436.159,350.597,427.023,350.597z" />
                                        </g>
                                    </g>
                                    <g>
                                        <g>
                                            <path d="M332.96,316.393H213.244c-4.427,0-8.017,3.589-8.017,8.017s3.589,8.017,8.017,8.017H332.96
                                                c4.427,0,8.017-3.589,8.017-8.017S337.388,316.393,332.96,316.393z" />
                                        </g>
                                    </g>
                                    <g>
                                        <g>
                                            <path d="M127.733,282.188H25.119c-4.427,0-8.017,3.589-8.017,8.017s3.589,8.017,8.017,8.017h102.614
                                                c4.427,0,8.017-3.589,8.017-8.017S132.16,282.188,127.733,282.188z" />
                                        </g>
                                    </g>
                                    <g>
                                        <g>
                                            <path d="M278.771,173.37c-3.13-3.13-8.207-3.13-11.337,0.001l-71.292,71.291l-37.087-37.087c-3.131-3.131-8.207-3.131-11.337,0
                                                c-3.131,3.131-3.131,8.206,0,11.337l42.756,42.756c1.565,1.566,3.617,2.348,5.668,2.348s4.104-0.782,5.668-2.348l76.96-76.96
                                                C281.901,181.576,281.901,176.501,278.771,173.37z" />
                                        </g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                </svg>
                            </div>
                            <h2>Fast Delivery</h2>
                        </div>
                        <div class="why-shop-box" >
                            <div class="img-box">
                                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 490.667 490.667" style="enable-background:new 0 0 490.667 490.667;" xml:space="preserve">
                                    <g>
                                        <g>
                                            <path d="M138.667,192H96c-5.888,0-10.667,4.779-10.667,10.667V288c0,5.888,4.779,10.667,10.667,10.667s10.667-4.779,10.667-10.667
                                                v-74.667h32c5.888,0,10.667-4.779,10.667-10.667S144.555,192,138.667,192z" />
                                        </g>
                                    </g>
                                    <g>
                                        <g>
                                            <path d="M117.333,234.667H96c-5.888,0-10.667,4.779-10.667,10.667S90.112,256,96,256h21.333c5.888,0,10.667-4.779,10.667-10.667
                                                S123.221,234.667,117.333,234.667z" />
                                        </g>
                                    </g>
                                    <g>
                                        <g>
                                            <path d="M245.333,0C110.059,0,0,110.059,0,245.333s110.059,245.333,245.333,245.333s245.333-110.059,245.333-245.333
                                                S380.608,0,245.333,0z M245.333,469.333c-123.52,0-224-100.48-224-224s100.48-224,224-224s224,100.48,224,224
                                                S368.853,469.333,245.333,469.333z" />
                                        </g>
                                    </g>
                                    <g>
                                        <g>
                                            <path d="M386.752,131.989C352.085,88.789,300.544,64,245.333,64s-106.752,24.789-141.419,67.989
                                                c-3.691,4.587-2.965,11.307,1.643,14.997c4.587,3.691,11.307,2.965,14.976-1.643c30.613-38.144,76.096-60.011,124.8-60.011
                                                s94.187,21.867,124.779,60.011c2.112,2.624,5.205,3.989,8.32,3.989c2.368,0,4.715-0.768,6.677-2.347
                                                C389.717,143.296,390.443,136.576,386.752,131.989z" />
                                        </g>
                                    </g>
                                    <g>
                                        <g>
                                            <path d="M376.405,354.923c-4.224-4.032-11.008-3.861-15.061,0.405c-30.613,32.235-71.808,50.005-116.011,50.005
                                                s-85.397-17.771-115.989-50.005c-4.032-4.309-10.816-4.437-15.061-0.405c-4.309,4.053-4.459,10.816-0.405,15.083
                                                c34.667,36.544,81.344,56.661,131.456,56.661s96.789-20.117,131.477-56.661C380.864,365.739,380.693,358.976,376.405,354.923z" />
                                        </g>
                                    </g>
                                    <g>
                                        <g>
                                            <path d="M206.805,255.723c15.701-2.027,27.861-15.488,27.861-31.723c0-17.643-14.357-32-32-32h-21.333
                                                c-5.888,0-10.667,4.779-10.667,10.667v42.581c0,0.043,0,0.107,0,0.149V288c0,5.888,4.779,10.667,10.667,10.667
                                                S192,293.888,192,288v-16.917l24.448,24.469c2.091,2.069,4.821,3.115,7.552,3.115c2.731,0,5.461-1.045,7.531-3.136
                                                c4.16-4.16,4.16-10.923,0-15.083L206.805,255.723z M192,234.667v-21.333h10.667c5.867,0,10.667,4.779,10.667,10.667
                                                s-4.8,10.667-10.667,10.667H192z" />
                                        </g>
                                    </g>
                                    <g>
                                        <g>
                                            <path d="M309.333,277.333h-32v-64h32c5.888,0,10.667-4.779,10.667-10.667S315.221,192,309.333,192h-42.667
                                                c-5.888,0-10.667,4.779-10.667,10.667V288c0,5.888,4.779,10.667,10.667,10.667h42.667c5.888,0,10.667-4.779,10.667-10.667
                                                S315.221,277.333,309.333,277.333z" />
                                        </g>
                                    </g>
                                    <g>
                                        <g>
                                            <path d="M288,234.667h-21.333c-5.888,0-10.667,4.779-10.667,10.667S260.779,256,266.667,256H288
                                                c5.888,0,10.667-4.779,10.667-10.667S293.888,234.667,288,234.667z" />
                                        </g>
                                    </g>
                                    <g>
                                        <g>
                                            <path d="M394.667,277.333h-32v-64h32c5.888,0,10.667-4.779,10.667-10.667S400.555,192,394.667,192H352
                                                c-5.888,0-10.667,4.779-10.667,10.667V288c0,5.888,4.779,10.667,10.667,10.667h42.667c5.888,0,10.667-4.779,10.667-10.667
                                                S400.555,277.333,394.667,277.333z" />
                                        </g>
                                    </g>
                                    <g>
                                        <g>
                                            <path d="M373.333,234.667H352c-5.888,0-10.667,4.779-10.667,10.667S346.112,256,352,256h21.333
                                                c5.888,0,10.667-4.779,10.667-10.667S379.221,234.667,373.333,234.667z" />
                                        </g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                </svg>
                            </div>
                            <h2>Free Shipping</h2>
                        </div>
                        <div class="why-shop-box" >
                            <div class="img-box">
                                <svg id="_30_Premium" height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg" data-name="30_Premium">
                                    <g id="filled">
                                        <path d="m252.92 300h3.08a124.245 124.245 0 1 0 -4.49-.09c.075.009.15.023.226.03.394.039.789.06 1.184.06zm-96.92-124a100 100 0 1 1 100 100 100.113 100.113 0 0 1 -100-100z" />
                                        <path d="m447.445 387.635-80.4-80.4a171.682 171.682 0 0 0 60.955-131.235c0-94.841-77.159-172-172-172s-172 77.159-172 172c0 73.747 46.657 136.794 112 161.2v158.8c-.3 9.289 11.094 15.384 18.656 9.984l41.344-27.562 41.344 27.562c7.574 5.4 18.949-.7 18.656-9.984v-70.109l46.6 46.594c6.395 6.789 18.712 3.025 20.253-6.132l9.74-48.724 48.725-9.742c9.163-1.531 12.904-13.893 6.127-20.252zm-339.445-211.635c0-81.607 66.393-148 148-148s148 66.393 148 148-66.393 148-148 148-148-66.393-148-148zm154.656 278.016a12 12 0 0 0 -13.312 0l-29.344 19.562v-129.378a172.338 172.338 0 0 0 72 0v129.38zm117.381-58.353a12 12 0 0 0 -9.415 9.415l-6.913 34.58-47.709-47.709v-54.749a171.469 171.469 0 0 0 31.467-15.6l67.151 67.152z" />
                                        <path d="m287.62 236.985c8.349 4.694 19.251-3.212 17.367-12.618l-5.841-35.145 25.384-25c7.049-6.5 2.89-19.3-6.634-20.415l-35.23-5.306-15.933-31.867c-4.009-8.713-17.457-8.711-21.466 0l-15.933 31.866-35.23 5.306c-9.526 1.119-13.681 13.911-6.634 20.415l25.384 25-5.841 35.145c-1.879 9.406 9 17.31 17.367 12.618l31.62-16.414zm-53-32.359 2.928-17.615a12 12 0 0 0 -3.417-10.516l-12.721-12.531 17.658-2.66a12 12 0 0 0 8.947-6.5l7.985-15.971 7.985 15.972a12 12 0 0 0 8.947 6.5l17.658 2.66-12.723 12.535a12 12 0 0 0 -3.417 10.516l2.928 17.615-15.849-8.231a12 12 0 0 0 -11.058 0z" />
                                    </g>
                                </svg>
                            </div>
                            <h2>Best Quality</h2>
                        </div>
                    </div>
               </div>
                <style>
                    .closing {
                        background: white;
                        
                    }
                    .outer-closing-div {
                        width: 50%;
                        margin: auto;
                        display: flex;
                        flex-direction: row;
                    }
                    .closing-div {
                        width: 50%;
                        text-align: left;
                        padding: 100px 0 100px 0;
                    }
                    .closing-div h2 {
                        color: #09294c;
                        font-size: 20px;
                        font-weight: 700;
                    }
                    .closing-div p {
                        color: #607087;
                        font-size: 16px;
                        font-weight: 200;
                    }
                </style>
                <div class="closing">
                    <div class="outer-closing-div">
                        <div class="closing-div">
                            <h2>A platform built for scale & <br>expansion. Start for free.</h2>
                        </div>
                        <div class="closing-div">
                            <p>Over the comming years, online business will <br>increase exponentially - and MusicSTORE is the <br>game changer.</p>
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
                                    if($user_name == "") {
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