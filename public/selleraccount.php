<?php 

    require "../private/autoload.php";

    $flag = 0;
    if(isset($_GET['sales']) && $_GET['sales'] == 'true') {
        $arr['s_id'] = $_SESSION['seller_id'];
        $query_orders = "select * from ordered_instrument inner join orders on orders.order_id = ordered_instrument.o_id where ordered_instrument.s_id =:s_id order by order_date desc;";
        $stmnt_orders= $con->prepare($query_orders);
        $check_orders = $stmnt_orders->execute($arr);

        if($check_orders) {
            $orders_array = $stmnt_orders->fetchAll(PDO::FETCH_OBJ);  //FETCH_ASSOC for array
        }
        $flag = 1;
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
            @keyframes topbar-anim {
                0% { clip-path: ellipse(15% 100% at 50% 0%);}
                25% {clip-path: ellipse(30% 100% at 50% 0%);}
                50% {clip-path: ellipse(45% 100% at 50% 0%);}
                75% {clip-path: ellipse(60% 100% at 50% 0%);}
                100% {clip-path: ellipse(75% 100% at 50% 0%);}
            }
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
            @keyframes data-anim {
                0% {transform: rotateY(90deg);}
                25% {transform: rotateY(60deg);}
                50% {transform: rotateY(45deg);}
                75% {transform: rotateY(30deg);}
                100% {transform: none;}
            }
            .data {
                width: 90%;
                background: white;
                min-width: 625px;
                margin: auto;
                z-index: 2;
                border-radius: 6px;
                box-shadow: 0 7px 20px rgba(50, 50, 93, .2);
                animation-name: data-anim;
                animation-duration: 400ms;
                animation-timing-function: linear;
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
            .data form {
                width: 80%;
                margin: auto;
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
            .data:hover {
                box-shadow: 0 10px 30px rgba(50, 50, 93, .2);
                transition: 500ms;
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
                    <li><a class="active" href="useraccount.php?orders=true">Orders</a></li>
                    <li><a class="active" href="cart.php">Cart</a></li>
                    <li><a class="active" href="useraccount.php"><?=$_SESSION['user_name']?></a></li>
                    <li><a class="active" href="selleraccount.php"><?=$_SESSION['company_name']?></a></li>
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

                    <?php
                        if($flag == 0) {
                    ?>
                    <div class="main-body">
                        <div class="data">
                            <div class="top-menu">
                                <div class="ul-div">
                                    <ul>
                                        <li><a class="active" href="selleraccount.php">Account</a></li>
                                        <li><a href="instruments.php">Instruments</a></li>
                                        <li><a href="selleraccount.php?sales=true">Sales</a></li>
                                    </ul>
                                </div>
                            </div>
                            <form method="post" action="updateseller.php">
                                <h2>Account details</h2>
                                <p style='color:blue; font-size:14px'>Fill only those fields which you want to update.</p>
                                <div class="item">
                                    <label for="email">Email:</label>
                                    <p class="fix-email" id="email" title="Cannot edit email"><?=$_SESSION['email']?></p>
                                </div>
                                <div class="item">
                                    <label for="username">Company Name:</label>
                                    <input type="text" class="form-control" id="username" name="company_name" placeholder="Company Name" value="<?=$_SESSION['company_name']?>" maxlength="100" title="Company Name">
                                </div>
                                <div class="item">
                                    <label for="contact">Contact:</label>
                                    <input type="text" name="seller_contact" id="contact" class="form-control" placeholder="Contact" value="<?=$_SESSION['seller_contact']?>" maxlength="10" title="Contact">
                                </div>
                                <div class="item">
                                    <label for="addres">Address:</label>
                                    <input type="text" name="seller_address" id="address" class="form-control" placeholder="Address" value="<?=$_SESSION['seller_address']?>" maxlength="250" title="Address">
                                </div>
                                <div class="item">
                                    <label for="pincode">PIN Code:</label>
                                    <input type="text" name="seller_pin_code" id="pincode" class="form-control" placeholder="PIN Code" value="<?=$_SESSION['seller_pin_code']?>" maxlength="6" title="Pin code">
                                </div>
                                <hr>
                                <input type="submit" class="submit-btn" value="Update" name="sellerupdate">
                                <!-- <hr> -->
                                <!-- <a href="#" class="deactivate">Deactivate Account</a> -->
                            </form>
                        </div>
                    </div>
                    <?php
                        } else if($flag == 1) {
                    ?>
                    <div class="main-body">
                        <div class="data">
                            <div class="top-menu">
                                <div class="ul-div">
                                    <ul>
                                        <li><a href="selleraccount.php">Account</a></li>
                                        <li><a href="instruments.php">Instruments</a></li>
                                        <li><a class="active" href="selleraccount.php?sales=true">Sales</a></li>
                                    </ul>
                                </div>
                            </div>
                            <style>
                                .orders {
                                    padding: 40px 20px;
                                }
                                .orders-table-container {
                                    width: 90%;
                                    margin: 20px auto;
                                }
                                .orders-table-container table {
                                    width: 100%;
                                }
                                .orders-table-container table th {
                                    border: 1px solid grey;
                                    text-align: center;
                                    padding: 5px;
                                    background: #88E0EF;
                                    text-transform: uppercase;
                                    color: #161E54;
                                }
                                .orders-table-container table tr,td {
                                    border: 1px solid grey;
                                    text-align: center;
                                    background: white;
                                }
                                .orders-table-container table td,th {
                                    width: 20%;
                                }
                            </style>
                        <div class="orders">
                            <?php
                                if(count($orders_array) > 0) {
                            ?>
                            <div class="orders-table-container">
                                <table>
                                    <tr>
                                        <th>Sr. No.</th>
                                        <th>Order ID</th>
                                        <th>Instrument ID</th>
                                        <th>Instrument Name</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Order date</th>
                                        <th>Payment Method</th>
                                    </tr>
                                <?php
                                    for($i = 0; $i < count($orders_array); $i++) {
                                ?>
                                    <tr>
                                        <td><?=$i+1?></td>
                                        <td><?=$orders_array[$i]->o_id?></td>
                                        <td><?=$orders_array[$i]->i_id?></td>
                                        <td><?=$orders_array[$i]->i_name?></td>
                                        <td><?=$orders_array[$i]->i_category?></td>
                                        <td><?=$orders_array[$i]->i_price?></td>
                                        <td><?=$orders_array[$i]->i_quantity?></td>
                                        <td><?=$orders_array[$i]->order_date?></td>
                                        <td><?=$orders_array[$i]->payment_method?></td>
                                    </tr>
                                <?php
                                    }
                                ?>
                                </table>
                            </div>
                            <?php
                                }
                            ?>
                        </div>
                        </div>
                    </div>
                    <?php
                        }
                    ?>
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