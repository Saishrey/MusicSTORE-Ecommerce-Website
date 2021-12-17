<?php 

    require "../private/autoload.php";

    //fetch instruments from cart

    // remove from cart 
    if(isset($_GET['remove_inst_id'])) {
            
        $array['cust_id'] = $_SESSION['user_id'];
        $array['i_id'] = $_GET['remove_inst_id'];

        $query = "delete from cart where customer_id=:cust_id and instrument_id=:i_id;";
        $stmnt = $con->prepare($query);
        $check = $stmnt->execute($array);

        if($check) {
            echo "<script>
                alert('Instrument deleted from cart.');
                window.location.replace('cart.php');
                </script>";
        } else {
            echo "<script>
                alert('Error deleting instrument from cart.');
                window.location.replace('cart.php');
                </script>";
        }
    }
    else if(isset($_GET['proceed_to_buy']) && $_GET['proceed_to_buy'] == 'true') {
        if(isset($_SESSION['checkout_inst_id'])) {
            unset($_SESSION['checkout_inst_id']);
        }

        $arr['user_id'] = $_SESSION['user_id'];
        $query = "select * from instrument where inst_id in (select instrument_id from cart where customer_id = :user_id);";
        $stmnt = $con->prepare($query);
        $check = $stmnt->execute($arr);

        if($check) {
            $inst_data = $stmnt->fetchAll(PDO::FETCH_OBJ);  //FETCH_ASSOC for array
            $total_price = 0;
            for($i = 0; $i < count($inst_data); $i++) {
                $total_price += $inst_data[$i]->price;
            }
            $_SESSION['checkout_total_price'] = $total_price;

            header('Location: checkout.php?select_address=true');
            die;
        }
    }
    else {
        $arr['user_id'] = $_SESSION['user_id'];
        $query = "select * from instrument where inst_id in (select instrument_id from cart where customer_id = :user_id);";
        $stmnt = $con->prepare($query);
        $check = $stmnt->execute($arr);

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
                background-color: rgb(239,243,246);
                font-family: 'Montserrat', sans-serif;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }
            header {
                /* background: #0082e6; */
                /* background: #181818; */
                background: rgba(24,24,24, 0.97);
                /* background: rgb(24,24,24); */
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
            main {
                padding: 80px 0 0 0;
            }
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
                /* background: linear-gradient(whitesmoke, #E6E6E6); */
                padding: 80px 20px 0 20px;
                display: flex;
                flex-direction: column;
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
                width: 70%;
                /* background: rgb(48,49,52); */
                background-color: rgb(239,243,246);
                min-width: 625px;
                margin: auto;
                position: relative;
                bottom: 130px;
                z-index: 2;
                height: auto;
                border-radius: 6px;
                box-shadow: 0 7px 20px rgba(50, 50, 93, .2);
                animation-name: data-anim;
                animation-duration: 400ms;
                animation-timing-function: linear;
            }
            .top-menu {
                width: 100%;
                background: #293A80;
                border-radius: 6px 6px 0 0;
                padding: 20px 40px;
                border-bottom: 1px solid #1b9bff;
            }
            .data .top-menu ul{
                list-style: none;
                display: flex;
                width: 100%;
                flex-direction: row;
                text-align: center;
            }
            .top-menu .ul-div {
                width: 60%;
                margin: auto;
            }
            .top-menu ul li {
                width: 33%;
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
            .data:hover {
                box-shadow: 0 10px 30px rgba(50, 50, 93, .2);
                transition: 500ms;
            }
            .data form h2 {
                font-size: 3rem;
                margin-bottom: 40px;
                /* color: black; */
                color: white;
            }
            .item{
                padding: 20px;
                font-size: 20px;
                width: 80%;
                /* margin: 15px; */
                border: none;
                outline: none;                
                color: whitesmoke;
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
                border-bottom: 2px solid whitesmoke;
                font-family: 'Montserrat', sans-serif;
                outline: none;
                float: right;
                color: white;
                /* background-color: #F1F3F4; */
                /* background-color: #B8C1C6; */
                /* background-color: #373737; */
                background: none;
            }
            .data form .update {
                text-decoration: none;
                /* color: #4A616B; */
                color: grey;
                margin-top: 20px;
                font-size: 14px;
            }
            .data form .update:hover {
                /* color: #1A73E8; */
                color: #1b9bff;
            }
            hr {
                width: 80%;
                margin: 30px;
            }
            .deactivate {
                text-decoration: none;
                padding: 12px 30px;
                width: 40%;
                margin-top: 15px;
                /* background-color: #32AEF2; */
                /* background: #181818; */
                background: none;
                color: white;
                font-size: 20px;
                font-family: 'Montserrat', sans-serif;
                text-transform: uppercase;
                text-align: center;
                border: 1px solid whitesmoke;
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
                color: white;
                font-size: 20px;
                font-family: 'Montserrat', sans-serif;
                text-transform: uppercase;
                border: 1px solid whitesmoke;
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
            .hidden-input {
                display:none;
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
                    <li><a class="active" href="useraccount.php?orders=true">Orders</a></li>
                    <li><a class="active" href="cart.php">Cart</a></li>
                    <li><a class="active" href="useraccount.php"><?=$_SESSION['user_name']?></a></li>
                    <?php 
                        if($_SESSION['is_seller'] == 1) {
                    ?>
                    <li><a class="active" href="selleraccount.php"><?=$_SESSION['company_name']?></a></li>
                    <?php
                        }
                    ?>
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
                    <style>
                        .inst-list {
                            width: 100%;
                            padding: 40px;
                        }
                        .empty-cart {
                            text-align: center;
                            color: grey;
                        }
                        .empty-cart h1 {
                            font-size: 55px;
                            padding: 20px;
                        }
                        .place-order {
                            width: 90%;
                            margin: auto;
                            padding: 20px;
                            text-align: center;
                        }
                        .place-order .no-order {
                            padding: 12px 30px;
                            width: 40%;
                            margin: auto;
                            margin-top: 20px;
                            margin-bottom: 20px;
                            /* background-color: #32AEF2; */
                            /* background: #181818; */
                            background: grey;
                            color: white;
                            font-size: 20px;
                            font-family: 'Montserrat', sans-serif;
                            text-transform: uppercase;
                            outline: none;
                            border-radius: 6px;
                        }
                        .can-order a {
                            padding: 12px 30px;
                            
                            font-size: 20px;
                            border: none;
                            /* border: 1px solid black; */
                            outline: none;
                            background: #F3950D;
                            border-radius: 6px;
                            color: white;
                            font-family: 'Montserrat', sans-serif;
                            text-transform: uppercase;
                            width: 100%;
                        }
                        .place-order .can-order { 
                            width: 40%;
                            margin: auto;
                            margin-top: 20px;
                            margin-bottom: 20px;
                            /* background-color: #32AEF2; */
                            /* background: #181818; */                           
                        }
                        .place-order .can-order a:hover {
                            cursor: pointer;
                            color: white;
                            background: rgb(187,103,54);
                            transition: 250ms linear;
                        }
                        .product-item {
                            display: flex;
                            flex-direction: row;
                            width: 90%;
                            margin: auto;
                            margin-top: 30px;
                            margin-bottom: 30px;
                            border-radius: 6px;
                            background: white;
                            box-shadow: 0 7px 10px rgba(50, 50, 93, .2);
                            /* border: 1px solid grey; */
                            /* background-color: rgb(239,243,246); */
                            /* box-shadow: white -10px -10px 20px 5px, rgb(24, 24, 24, 0.2) 10px 10px 20px 5px ; */
                        }
                        .buy-cart-inst-img-cont  {
                            padding: 30px;
                            width: 30%;
                            /* min-width: 470px; */
                        }
                        .main-square-img {
                            /* margin: 10px; */
                            width: 100%;
                            height: 250px;
                            text-align: center;
                            overflow: hidden;
                            border: 1px solid #1b9bff;
                        }
                        .main-square-img img{
                            /* clip-path: circle(); */
                            max-width:100%;
                            max-height:100%;
                            vertical-align: middle;
                            /* height: 100%; */
                        }
                        .hurry-up {
                            width: 100%;
                            text-align: left;
                            padding: 10px;
                            color: #4E9F3D;
                        }
                        .inst-details-buy-cart {
                            width: 70%;
                            padding: 30px;
                        }
                        .inst-details-buy-cart h1 {
                            width: 100%;
                            font-weight: 300;
                            font-size: 38px;
                            padding-bottom: 10px;
                        }
                        .inst-details-buy-cart h1 a {
                            color: black;
                        }
                        .inst-details-buy-cart h1 a:hover {
                            text-decoration: underline;
                            color: purple;
                        }
                        .sold-by {
                            width: 100%;
                            color: #1b9bff;
                            font-weight: 200;
                            font-size: 16px;
                            text-align: right;
                            padding-bottom: 10px;
                        }
                        .market-price {
                            color: grey;
                            font-size: 16px;
                            font-weight: 200;
                            padding: 40px 0 10px 0;
                        }
                        .og-price {
                            /* color: red; */
                            font-weight: 400;
                            font-size: 22px;
                            padding-bottom: 10px;
                        }
                        .you-save {
                            font-weight: 400;
                            font-size: 16px;
                            padding-bottom: 10px;
                        }
                        .buy-cart-btns {
                            display: flex;
                            flex-direction: column;
                            width: 100%;
                            padding: 40px 0;
                        }
                        .buy-cart-btns form {
                            width: 100%;
                            padding: 0;
                            text-align: center;
                        }
                        .cart-btn {
                            text-decoration: none;
                            width: 80%;
                            padding: 15px;
                            background: none;
                            border: none;
                            font-family: 'Montserrat', sans-serif;
                            color: purple;
                            font-size: 18px;
                            text-align:center;
                            margin: auto;
                            /* margin-top: 25px; */
                            border-radius: 3px;
                        }
                        .sold-out {
                            text-align: center;
                            padding: 20px;
                            font-size: 22px;
                            color: red;
                        }
                        .cart-btn:hover {
                            cursor: pointer;
                            color: #1b9bff;
                            transition: 250ms linear;
                        }
                    </style>
                    <div class="main-body">
                        <div class="data">
                            <div class="top-menu">
                                <div class="ul-div">
                                    <ul>
                                        <li><a href="useraccount.php">My Account</a></li>
                                        <li><a href="useraccount.php?orders=true">Orders</a></li>
                                        <li><a class="active" href="cart.php">Cart</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="inst-list">
                                <?php
                                    if(count($inst_data) == 0) {
                                ?>
                                <div class="empty-cart">
                                    <h1><i class="fa fa-shopping-cart"></i></h1>
                                    <p>Your cart is empty.</p>
                                </div>
                                <?php
                                    } else {
                                        $flag = 0;
                                        for($i = 0; $i < count($inst_data); $i++) {
                                            
                                            if($inst_data[$i]->quantity == 0) {
                                                $flag++;
                                            } 
                                ?>
                                <div class="product-item" <?php if($inst_data[$i]->quantity == 0) { ?> style="background: #D3E0EA;" <?php } ?>>
                                    <div class="buy-cart-inst-img-cont">
                                        <div class="main-square-img">
                                            <img src="../private/uploads/<?=$inst_data[$i]->inst_img?>" alt="Image">
                                        </div>
                                    </div>
                                    <div class="inst-details-buy-cart" <?php if($inst_data[$i]->quantity == 0) { ?> style="color: grey;" <?php } ?>>
                                        <h1 style="text-transform: capitalize;"><a href="productPage.php?inst_id=<?=$inst_data[$i]->inst_id?>&category=<?=$inst_data[$i]->category?>" target="_blank"><?=$inst_data[$i]->inst_name?></a></h1>
                                        <h3 class="sold-by">Sold by: <?=$inst_data[$i]->brand_name?></h3>
                                        <h2 class="og-price">Price: <span style="color: red;">&#8377; <?=$inst_data[$i]->price?></h2>
                                        <?php
                                            if($inst_data[$i]->quantity > 0 && $inst_data[$i]->quantity <= 5) {
                                        ?>
                                        <div class="hurry-up">
                                            <h3>Hurry up! Only <?=$inst_data[$i]->quantity?> pieces left.</h3>
                                        </div>
                                        <?php
                                                }
                                        ?>
                                        <div class="buy-cart-btns">
                                            <?php
                                                if($inst_data[$i]->quantity == 0) {
                                            ?>
                                            <p class="sold-out">Sold out!</p>
                                            <?php
                                                }
                                            ?>
                                            <a href="cart.php?remove_inst_id=<?=$inst_data[$i]->inst_id?>" class="cart-btn"><i class="fa fa-trash"></i> Remove from cart</a>
                                        </div>
                                    </div>   
                                </div>
                                <?php 
                                        }   
                                        if($flag > 0) {
                                ?>
                                <div class="place-order">
                                    <p class="no-order" title="Remove instruments from cart which are sold out.">Proceed to buy</p>
                                </div>
                                <?php 
                                        } else {
                                ?>
                                <div class="place-order">
                                    <p class="can-order"><a href="cart.php?proceed_to_buy=true">Proceed to buy</a></p>
                                </div>
                                <?php 
                                        }
                                ?>
                            </div>
                                <?php
                                }
                                ?>
                            </div>
                           
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
</html>