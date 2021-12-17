<?php
    require "../private/autoload.php";

    $flag = 0;

    if(isset($_GET['agent_query']) && $_GET['agent_query'] == 'orders') {
        // fetch all orders 
        $arr['a_id'] = $_SESSION['agent_id'];
        $query_orders = "select * from orders inner join customer on orders.cust_id = customer.user_id where orders.delivery_agent_id = :a_id and orders.is_delivered =0";
        $stmnt_orders= $con->prepare($query_orders);
        $check_orders = $stmnt_orders->execute($arr);

        if($check_orders) {
            $orders_array = $stmnt_orders->fetchAll(PDO::FETCH_OBJ);  //FETCH_ASSOC for array
        }
        $flag = 1;
    }
    else if(isset($_GET['moreDetails'])) {
         // fetch all orders 
         $arr['o_id'] = $_GET['moreDetails'];
         $query_orders = "select * from ordered_instrument where o_id=:o_id";
         $stmnt_orders= $con->prepare($query_orders);
         $check_orders = $stmnt_orders->execute($arr);
 
         if($check_orders) {
             $inst_array= $stmnt_orders->fetchAll(PDO::FETCH_OBJ);  //FETCH_ASSOC for array
         }
        $flag = 2;
    } else if(isset($_GET['agent_query']) && $_GET['agent_query'] == 'delivered') {
        // fetch all orders 
        $arr['a_id'] = $_SESSION['agent_id'];
        $query_orders = "select * from orders inner join ordered_instrument on orders.order_id = ordered_instrument.o_id inner join customer on orders.cust_id = customer.user_id where orders.delivery_agent_id = :a_id and orders.is_delivered=1 order by is_delivered, order_date desc;";
        $stmnt_orders= $con->prepare($query_orders);
        $check_orders = $stmnt_orders->execute($arr);

        if($check_orders) {
            $orders_array = $stmnt_orders->fetchAll(PDO::FETCH_OBJ);  //FETCH_ASSOC for array

        }
        $flag = 3;
    } 
    else if(isset($_POST['delivered']) && $_POST['delivered'] == 'true') {
        // fetch all orders 
        $delivered_date = date('F j, Y');
        $arr['delivered_date'] = $delivered_date;
        $arr['o_id'] = $_POST['order_id'];
        $query = "update orders set is_delivered=1, delivered_date=:delivered_date where order_id=:o_id";
        $stmnt= $con->prepare($query);
        $check_orders = $stmnt->execute($arr);

        // send email to customer
        $email = $_POST['cust_email'];
        $cust_name = $_POST['cust_name'];
        sendDeliveredEmail($email, $cust_name);

        $flag = 4;
    } 
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css">
        <!-- <link rel="stylesheet" href="authentication_style.css"> -->
        <title>Agent | MUSICSTORE</title>
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
                /* min-height: 100vh; */
                display: flex;
                background: url(images/del_agent.jpg) no-repeat;
                background-size: cover;
                flex-direction: column;
                height: 100%;
                width: 100%;
                /* left:0;
                right: 0;
                top: 0;
                bottom: 0;
                position: absolute; */
            }
            header {
                /* background: #0082e6; */
                /* background: #181818; */
                background: rgba(239,243,246, 0.9);
                /* position: fixed; */
                z-index: 3;
                /* opacity: 0.7; */
                height: 80px;
                width: 100%;
                border-bottom: 1px solid #1b9bff;
            }
            
            .logo {
                color: black;
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
                background: rgba(255,255,255, 0.9);
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
                color: black;
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
        </style>
        <header class="navbar-fixed"> 
        <input type="checkbox" id="check">
                <label for="check" class="checkbtn">
                    <i class="fa fa-bars"></i>
                </label>
                <a class="logo" href="adminPage.php">Music<span style="color:#1b9bff;">STORE</span>&trade;<sub style="font-size:18px;">Agent</sub></a>
                <ul class="nav-list">
                    <li><a href="logout.php">Sign out</a></li>
                </ul>                
        </header>
        <style>
            main {
                padding: 20px 0 80px 0;
                /* background: url(images/user_account);
                background-size: cover; */
            }
            main .main-body {
                /* background: linear-gradient(whitesmoke, #E6E6E6); */
                padding: 80px 20px 0 20px;
                display: flex;
                flex-direction: column;
                /* padding-bottom: 80px; */
            }
            .data {
                width: 60%;
                background: rgb(239,243,246);
                min-width: 1400px;
                margin: auto;
                z-index: 2;
                border-radius: 6px;
                box-shadow: 0 7px 20px rgba(50, 50, 93, .2);
            }
            .top-menu {
                width: 100%;
                border-radius: 6px 6px 0 0;
                background: #1687A7;
                /* background: rgb(24,24,24); */
                padding: 20px 40px;
            }
            .data .top-menu ul{
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
                list-style: none;
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
                color: whitesmoke;
            }
            .top-menu ul li .active {
                color: #0A043C;
                border-bottom: 2px solid white;
            }
            .top-menu ul li a:hover {
                color: #0A043C;
                border-bottom: 2px solid white;
            }

            .inst-up-rem {
                display: flex;
                flex-direction: row;
                width: 100%;
                padding: 40px;
            }
            .inst-up-rem .upload {
                text-decoration: none;
                padding: 10px 40px;
                background: #3DB026;
                color: white;
                margin: auto;
                width: 50%;
                text-align:center;
                border-radius: 3px;
            }
            .inst-up-rem .upload:hover {
                background: #369b22;
            }
            .inst-up-rem .remove-disabled {
                text-decoration: none;
                padding: 10px 40px;
                background: grey;
                color: white;
                margin: 30px;
                width: 50%;
                text-align:center;
                border-radius: 3px;
            }
            .inst-up-rem .remove {
                text-decoration: none;
                padding: 10px 40px;
                background: #F3950D;
                color: white;
                margin: 30px;
                width: 50%;
                text-align:center;
                border-radius: 3px;
            }
            .inst-up-rem .remove:hover {
                background: #FF7800;
            }
            .inst-container {
                display: flex;
                width: 90%;
                margin: auto;
                flex-direction: row;
                background: #B8E4F0;
                margin-top: 20px;
                border-radius: 6px;
                box-shadow: 0 7px 10px rgba(50, 50, 93, .2);
            }
            .inst-table {
                width: 70%;
                margin: auto;
                text-align: center;
                display: flex;
                flex-direction: column;
            }
            .inst-table p {
                text-align: center;
                color: grey;
            }
            .inst-table table {
                width: 80%;
                margin: auto;
                border: 3px solid grey;
                border-collapse: collapse;
            }
            .inst-table th {
                border: 1px solid grey;
                text-align: center;
                padding: 10px;
                background: #88E0EF;
                text-transform: uppercase;
                color: #161E54;
            }
            .inst-table td, tr {
                border: 1px solid grey;
                text-align: center;
                background: white;
                padding: 10px;
            }
            .inst-table td {
                width: 30%;
            }
            .inst-table .sr-no {
                width: 10%;
            }
            .inst-count {
                margin: auto;
                text-align: center;
                padding: 20px;
            }
            .buy-cart-inst-img-cont  {
                padding: 30px;
                width: 30%;
                /* min-width: 470px; */
            }
            .main-square-img {
                /* margin: 10px; */
                width: 100%;
                height: 300px;
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
            .rem-btn {
                padding: 40px;
            }
            .remove-inst {
                text-decoration: none;
                padding: 10px 40px;
                background: #F3950D;
                color: white;
                margin-top: 30px;
                width: 50%;
                text-align:center;
                border-radius: 3px;
            }
            .remove-inst:hover {
                background: #FF7800;
            }

        </style>
        <style>
            .empty-cart {
                text-align: center;
                color: grey;
                padding: 20px;
                margin: 10px auto;

            }
            .hidden-input {
                display: none;
            }
            .empty-cart h1 {
                font-size: 55px;
            }
            .count-orders {
                width: 100%;
                text-align: center;
                padding: 20px;
                margin: 10px auto;
            }
            .orders {
                padding: 40px;
            }
            .orders a {
                text-decoration: none;
            }
            .order-item form {
                text-align: center;
                padding: 20px;
            }
            .order-item {
                width: 80%;
                margin: 40px auto;
                border-radius: 6px;
                padding: 20px;
                background: none;
                box-shadow: white -10px -10px 20px 5px, rgb(24, 24, 24, 0.2) 10px 10px 20px 5px ;
            }
            .order-item h2 {
                color: rgb(24,24,24);
                padding: 10px;
            }
            .order-item h3 {
                color: rgb(24,24,24);
                padding: 10px;
                font-weight: 100;
            }
            .order-item p {
                color: rgb(24,24,24);
                text-align: center;
                padding: 20px;
            }
            .delivered-btn {
                padding: 10px 16px;
                color: white;
                border: none;
                outline: none;

                background: #3DB026;
                text-transform: uppercase;
                font-size: 22px;
                font-weight: 700;
                border-radius: 6px;
            }
            .delivered-btn:hover {
                cursor: pointer;
                background: #369b22;
                transition: 0.3s;
            }
            .more-details {
                color: purple;
                font-size: 20px;
            }
            .more-details:hover {
                color: #1b9bff;
                transition: 250ms linear;
            }
        </style>
        <main id="top">
            <?php
                if($flag == 0) { //Agent details
            ?>
             <style>
                 .account-details {
                    text-align: center;
                    width: 60%;
                    margin: 20px auto;
                    padding: 40px 0;
                }    
                .account-details .item {
                    width: 100%;
                }
                .account-details h2 {
                    font-size: 3rem;
                    margin-bottom: 40px;
                    /* color: black; */
                    color: rgb(50,50,93);
                }
                
                .item{
                    padding: 20px;
                    display: flex;
                    flex-direction: row;
                    font-size: 20px;
                    width: 80%;
                    margin: auto;
                    /* margin: 15px; */
                    border: none;
                    outline: none;                
                    color: rgb(96,108,138);
                    /* background-color: #F1F3F4; */
                    /* background-color: #B8C1C6; */
                }
                .item h3 {
                    width: 30%;
                    text-align: right;
                    padding-right: 100px;
                }
                .item .update-submit {
                    text-align: center;
                    width: 20%;
                    color: purple;
                }
                .item a {
                    text-align: center;
                    width: 20%;
                    color: purple;
                }
                .item a:hover {
                    transition: 250ms linear;
                    color: #1b9bff;
                }
                .item .fix-email {
                    padding: 5px;
                    font-size: 18px;
                    max-width: 1000px;
                    width:70%;
                    /* margin: 15px; */
                    border: none;
                    /* border-bottom: 2px solid grey; */
                    font-family: 'Montserrat', sans-serif;
                    outline: none;
                    float: right;
                    text-align: left;
                    padding-left: 100px;
                    color: grey;
                    border-bottom: 2px solid grey;
                    /* background-color: #F1F3F4; */
                    /* background-color: #B8C1C6; */
                    /* background-color: #373737; */
                    background: none;
                }     
                .form-control {
                    padding: 5px 2px;
                    font-size: 18px;
                    max-width: 1000px;
                    width: 50%;
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
                .agent-upload {
                    margin-top: 50px;
                    width: 100%;
                }  
                
                .account-details > h3 {
                    font-size: 24px;
                    padding: 10px 5px;
                    color: rgb(96,108,138);
                    width: max-content;
                    position: relative;
                    bottom: 27px;
                    left: 50px;
                    background-color: rgb(239,243,246);
                }
            </style>
                <div class="user-info">
                    <div class="main-body">
                        <div class="data">
                            <div class="top-menu">
                                <div class="ul-div">
                                    <ul>
                                        <li><a class="active" href="agentPage.php">My Profile</a></li>
                                        <li><a href="agentPage.php?agent_query=orders">Current Orders</a></li>
                                        <li><a href="agentPage.php?agent_query=delivered">Delivered</a></li>
                                    </ul>
                                </div>
                            </div>
                                <div class="account-details">
                                    <h2>Account details</h2>
                                    <div class="item">
                                        <h3>ID:</h3>
                                        <p class="fix-email" id="email" title="Cannot edit email"><?=$_SESSION['agent_id']?></p>
                                    </div>
                                    <div class="item">
                                        <h3>Name:</h3>
                                        <p class="fix-email" class="form-control"  ><?=$_SESSION['agent_name']?></p>
                                    </div>
                                    <div class="item">
                                        <h3>Contact:</h3>
                                        <p class="fix-email" id="contact" class="form-control"  ><?=$_SESSION['agent_contact']?></p>
                                    </div>
                                    <div class="item">
                                        <h3>City:</h3>
                                        <p class="fix-email" id="address" class="form-control"  ><?=$_SESSION['agent_city']?></p>
                                    </div>
                                    <div class="item">
                                        <h3>State:</h3>
                                        <p class="fix-email" id="address" class="form-control"  ><?=$_SESSION['agent_state']?></p>
                                    </div>
                                    <div class="item">
                                        <h3>Country:</h3>
                                        <p class="fix-email" id="address" class="form-control"  ><?=$_SESSION['agent_country']?></p>
                                    </div>
                                    <div class="item">
                                        <h3>PIN Code:</h3>
                                        <p class="fix-email" id="pincode" class="form-control"  ><?=$_SESSION['agent_pin_code']?></p>
                                    </div>
                                </div>                    
                        </div>
                    </div>
                </div>
            <?php
                } else if($flag == 1) { // Orders
            ?>
            <div class="user-info">
                    <div class="main-body">
                        <div class="data">
                            <div class="top-menu">
                                <div class="ul-div">
                                    <ul>
                                        <li><a href="agentPage.php">My Profile</a></li>
                                        <li><a class="active" href="agentPage.php?agent_query=orders">Current Orders</a></li>
                                        <li><a href="agentPage.php?agent_query=delivered">Delivered</a></li>
                                    </ul>
                                </div>
                            </div> 
                            
                            <div class="orders">
                                <?php
                                    if(is_array($orders_array) && count($orders_array) > 0) {
                                ?>
                                <div class="count-orders">
                                    <p>Number of orders = <?=count($orders_array)?></p>
                                </div>
                                <?php
                                    for($i = 0; $i < count($orders_array); $i++) {
                                ?>
                                <div class="order-item">
                                    <h2>Order id: <span style="color: purple;"><?=$orders_array[$i]->order_id?></span></h2>
                                    <h3>Customer Name: <?=$orders_array[$i]->user_name?></h3>
                                    <h3>Customer Contact: +91-<?=$orders_array[$i]->contact?></h3>
                                    <h3>Delivery Address: <?=$orders_array[$i]->delivery_address?></h3>
                                    <h3>Payment Method: <?=$orders_array[$i]->payment_method?></h3>
                                    <h3>Total amount: <span style="color: red;">&#8377; <?=$orders_array[$i]->total_amount?></span> </h3>
                                    <h3>Ordered Date: <span style="color: #22577E;"><?=$orders_array[$i]->order_date?></span></h3>
                                    <h2>Delivery Date: <span style="color: #22577E;"><?=$orders_array[$i]->delivery_date?></span></h2>
                                    <p><a href="agentPage.php?moreDetails=<?=$orders_array[$i]->order_id?>" class="more-details">Click here for more details</a></p>
                                    <form action="agentPage.php" method="post">
                                        <input type="text" class="hidden-input" name="order_id" value="<?=$orders_array[$i]->order_id?>">
                                        <input type="text" class="hidden-input" name="cust_email" value="<?=$orders_array[$i]->email?>">
                                        <input type="text" class="hidden-input" name="cust_name" value="<?=$orders_array[$i]->user_name?>">
                                        <input type="text" class="hidden-input" name="delivered" value="true">
                                        <input type="submit" value="Delivered" class="delivered-btn">
                                    </form>
                                </div>
                                <?php
                                    }
                                    } else {
                                ?>
                                <div class="empty-cart">
                                    <h1><i class="fa fa-truck" ></i></h1>
                                    <p>You have no orders to deliver.</p>
                                </div>
                                <?php
                                    
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
                } else if($flag == 2) { // more details
            ?>
            <div class="user-info">
                    <div class="main-body">
                        <div class="data">
                            <div class="top-menu">
                                <div class="ul-div">
                                    <ul>
                                        <li><a href="agentPage.php">My Profile</a></li>
                                        <li><a class="active" href="agentPage.php?agent_query=orders">Current Orders</a></li>
                                        <li><a href="agentPage.php?agent_query=delivered">Delivered</a></li>
                                    </ul>
                                </div>
                            </div>   
                            <style>
                                .empty-cart {
                                    text-align: center;
                                    color: grey;
                                }
                                .empty-cart h1 {
                                    font-size: 55px;
                                    padding: 20px;
                                }
                                .orders-container {
                                    padding: 40px 0;
                                }
                                .inst-container {
                                    display: flex;
                                    width: 60%;
                                    flex-direction: row;
                                    background: rgb(239,243,246);
                                    margin: 40px auto;
                                    border-radius: 6px;
                                    box-shadow: white -10px -10px 20px 5px, rgb(24, 24, 24, 0.2) 10px 10px 20px 5px ;
                                }
                                .inst-count {
                                    margin: auto;
                                    text-align: center;
                                    padding: 20px;
                                }
                                .buy-cart-inst-img-cont  {
                                    padding: 30px;
                                    width: 40%;
                                    /* min-width: 470px; */
                                }
                                .main-square-img {
                                    /* margin: 10px; */
                                    width: 100%;
                                    height: 200px;
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
                                .sub-div > ul {
                                    list-style: none;
                                    margin: auto;
                                    display: block;
                                    font-size: 18px;
                                }
                                .sub-div > ul li {
                                    padding: 10px 10px 10px 0;
                                    color: #000D6B;
                                }
                                .inst-details-buy-cart {
                                    width: 60%;
                                    padding: 30px;
                                }
                                .inst-details-buy-cart h1 {
                                    width: 100%;
                                    font-weight: 300;
                                    font-size: 38px;
                                    padding-bottom: 10px;
                                }
                                .sold-by {
                                    width: 100%;
                                    color: #1b9bff;
                                    font-weight: 200;
                                    font-size: 16px;
                                    text-align: center;
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
                            </style> 
                            <p class="inst-count">Number of instruments: <?=count($inst_array)?></p>
                            <p class="inst-count">Order id: <?=$_GET['moreDetails']?></p>
                            
                            <?php
                                for($i = 0; $i < count($inst_array); $i++) {
                            ?>
                            <div class="inst-container">
                                <div class="buy-cart-inst-img-cont">
                                    <div class="main-square-img">
                                        <img src="../private/uploads/<?=$inst_array[$i]->i_img_name?>" alt="Image">
                                    </div>
                                </div>
                                <div class="inst-details-buy-cart">
                                    <h1 style="text-transform: capitalize;"><?=$inst_array[$i]->i_name?></h1>
                                    <h3 class="sold-by">Sold by: <?=$inst_array[$i]->i_brand_name?></h3>     
                                    <div class="price-and-buy">
                                        <div class="sub-div">
                                            <h2>Price: <span style="color: red;">&#8377; <?=$inst_array[$i]->i_price?></span> </h2>
                                            <ul>
                                                <li> <p>Inst id: <span style="color: grey;"><?=$inst_array[$i]->i_id?></span></p> </li>
                                                <li> <p>Quantity: 1</p></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>   
                            </div>
                            <?php
                                }
                            ?>
                            <style>
                                .back-contain {
                                    text-align: center;
                                    padding: 40px;
                                }
                                .back {
                                    color: purple;
                                    text-decoration: none;

                                    font-size: 20px;
                                }
                                .back:hover {
                                    color: #1b9bff;
                                    transition: 250ms linear;
                                }
                            </style>
                            <p class="back-contain"><a href="agentPage.php?agent_query=orders" class="back">Back</a></p>
                        </div> 
                    </div>  
        </div>
                    
            <?php
                } else if($flag == 3) { // list of delivered orders
            ?>
            <div class="user-info">
                    <div class="main-body">
                        <div class="data">
                            <div class="top-menu">
                                <div class="ul-div">
                                    <ul>
                                        <li><a href="agentPage.php">My Profile</a></li>
                                        <li><a href="agentPage.php?agent_query=orders">Current Orders</a></li>
                                        <li><a class="active" href="agentPage.php?agent_query=delivered">Delivered</a></li>
                                    </ul>
                                </div>
                            </div> 
                            <style>
                                .empty-cart {
                                    text-align: center;
                                    color: grey;
                                }
                                .empty-cart h1 {
                                    font-size: 55px;
                                    padding: 20px;
                                }
                                .orders-container {
                                    padding: 40px 0;
                                    
                                }
                                .inst-container {
                                    display: flex;
                                    width: 75%;
                                    flex-direction: row;
                                    /* background: rgb(239,243,246); */
                                    background: #B8E4F0;
                                    margin: 40px auto;
                                    border-radius: 6px;
                                    box-shadow: white -10px -10px 20px 5px, rgb(24, 24, 24, 0.2) 10px 10px 20px 5px ;
                                }
                            
                                .inst-count {
                                    margin: auto;
                                    text-align: center;
                                    padding: 20px;
                                }
                                .buy-cart-inst-img-cont  {
                                    padding: 30px;
                                    width: 30%;
                                    /* min-width: 470px; */
                                }
                                .main-square-img {
                                    /* margin: 10px; */
                                    width: 100%;
                                    height: 200px;
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
                                .sub-div > ul {
                                    list-style: none;
                                    margin: auto;
                                    display: block;
                                    font-size: 18px;
                                }
                                .sub-div > ul li {
                                    padding: 5px 10px 5px 0;
                                    color: #000D6B;
                                }
                                .inst-details-buy-cart {
                                    width: 70%;
                                    padding: 30px;
                                    background: none;
                                }
                                .inst-details-buy-cart h1 {
                                    width: 100%;
                                    font-weight: 300;
                                    font-size: 30px;
                                    padding-bottom: 10px;
                                }
                                .inst-details-buy-cart h1 a {
                                    color: black;
                                }
                                .inst-details-buy-cart h1 a:hover {
                                    color: purple;
                                    text-decoration: underline;
                                }
                                .sold-by {
                                    width: 100%;
                                    color: #1b9bff;
                                    font-weight: 200;
                                    font-size: 16px;
                                    text-align: center;
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
                            </style>
                            <div class="orders-container">
                            <?php
                                if(count($orders_array) == 0 ) {
                            ?>
                            <div class="empty-cart">
                            <h1><i class="fa fa-truck"></i></i></h1>
                                <p>You haven't delivered any orders.</p>
                            </div>
                            <?php
                                } else {
                            ?>
                            <p class="inst-count">Number of delivered orders: <?=count($orders_array)?></p>
                            
                            <?php
                                for($i = 0; $i < count($orders_array); $i++) {
                            ?>
                            <div class="inst-container" id="inst-container">
                                <div class="buy-cart-inst-img-cont">
                                    <div class="main-square-img">
                                        <img src="../private/uploads/<?=$orders_array[$i]->i_img_name?>" alt="Image">
                                    </div>
                                </div>
                                <div class="inst-details-buy-cart">
                                    <h1 >Order id: <span style="color: grey;"><?=$orders_array[$i]->order_id?></span></h1>   
                                    <div class="price-and-buy">
                                        <div class="sub-div">
                                            <ul>
                                                <li> <p style="text-transform: capitalize;">Instrument: <?=$orders_array[$i]->i_name?></p> </li>
                                                <li> <p style="text-transform: capitalize;">Sold by: <?=$orders_array[$i]->i_brand_name?></p> </li>
                                                <li><p>Price: <span style="color: red;">&#8377; <?=$orders_array[$i]->i_price?></span> </p></li>
                                                <li> <p style="text-transform: capitalize;">Customer: <?=$orders_array[$i]->user_name?></p> </li>
                                                <li> <p style="text-transform: capitalize;">Contact: <?=$orders_array[$i]->contact?></p> </li>
                                                <li> <p style="text-transform: capitalize;">Delivery address: <?=$orders_array[$i]->delivery_address?></p> </li>
                                                
                                                <li><h2><span style="color: #FF5403;">Delivered on <?=$orders_array[$i]->delivered_date?> <i class="fa fa-check-circle"></i></span></h2></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>   
                            </div>
                            <?php
                                        }
                                    } 
                            ?>
                            </div>
                        </div>
                    </div>
                </div>
            
            <?php
                } else if($flag == 4) { //Delivered
            ?>
                <script>
                    myVar= setTimeout(function() {
                        document.getElementById('redirect').classList.remove('hide');
                    }, 1000);
                    document.getElementById("orderPlaced").onload = setTimeout(function() {
                        window.location.replace("agentPage.php?agent_query=delivered");
                    }, 4000);
                </script>
                <div class="user-info">
                    <div class="main-body">
                        <div class="data">
                            <div class="top-menu">
                                <div class="ul-div">
                                    <ul>
                                        <li><a href="agentPage.php">My Profile</a></li>
                                        <li><a class="active" href="agentPage.php?agent_query=orders">Current Orders</a></li>
                                        <li><a href="agentPage.php?agent_query=delivered">Delivered</a></li>
                                    </ul>
                                </div>
                            </div> 
                            <style>
                                .data {
                                    height: 550px;
                                }
                                .order-success {
                                    padding: 80px;
                                }
                                .order-success h1 {
                                    text-align: center;
                                    color: rgb(50,50,93);
                                    margin: 5px auto;
                                    font-size: 30px;
                                    /* position: relative; */
                                    animation-name: h1-anim;
                                    animation-duration: 400ms;
                                    animation-timing-function: linear;
                                }
                                @keyframes h1-anim {
                                    0% {
                                        font-size: 60px;
                                        opacity: 0.2;
                                    }
                                    100% {
                                        font-size: 30px;
                                        opacity: none;
                                    }
                                }
                            </style>
                        <div class="order-success">
                            <style>
                                .checkmark__circle {
                                stroke-dasharray: 166;
                                stroke-dashoffset: 166;
                                stroke-width: 50;
                                stroke-miterlimit: 10;
                                stroke: #22577E;
                                fill: none;
                                animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
                                }

                                .checkmark {
                                width: 200px;
                                height: 200px;
                                border-radius: 50%;
                                display: block;
                                stroke-width: 5;
                                stroke: #fff;
                                stroke-miterlimit: 10;
                                margin: 2% auto;
                                box-shadow: inset 0px 0px 0px #22577E;
                                animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
                                }

                                .checkmark__check {
                                transform-origin: 50% 50%;
                                stroke-dasharray: 48;
                                stroke-dashoffset: 48;
                                animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
                                }

                                @keyframes stroke {
                                100% {
                                    stroke-dashoffset: 0;
                                }
                                }
                                @keyframes scale {
                                0%, 100% {
                                    transform: none;
                                }
                                50% {
                                    transform: scale3d(1.1, 1.1, 1);
                                }
                                }
                                @keyframes fill {
                                100% {
                                    box-shadow: inset 0px 0px 0px 30px #7ac142;
                                }
                                }
                                #loader {
                                border: 7px solid #D8E3E7;
                                border-radius: 50%;
                                border-top: 7px solid #3498db;
                                width: 40px;
                                height: 40px;
                                -webkit-animation: spin 600ms linear infinite; /* Safari */
                                animation: spin 600ms linear infinite;
                                }

                                /* Safari */
                                @-webkit-keyframes spin {
                                0% { -webkit-transform: rotate(0deg); }
                                100% { -webkit-transform: rotate(360deg); }
                                }

                                @keyframes spin {
                                0% { transform: rotate(0deg); }
                                100% { transform: rotate(360deg); }
                                }
                                .redirect {
                                    display: flex;
                                    flex-direction: row;
                                    width: 50%;
                                    margin: 20px auto;
                                }
                                .redirect p {
                                    width: 55%;
                                    text-align: right;
                                    padding: 10px 20px;
                                }
                                .loader-container {
                                    width: 45%;
                                }
                                .hide {
                                    display: none;
                                }
                            </style>
                            <svg class="checkmark" id="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                                <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                            </svg>
                            <h1 id="orderPlaced">Order Delivered.</h1>
                            <div class="redirect hide" id="redirect">
                                <p>Redirecting</p>
                                <div class="loader-container">
                                    <div id="loader"></div>
                                </div>
                            </div>
                        </div>
                            
                
                        </div>
                    </div>
                </div>
            <?php
                }
            ?>
        </main>

    </body>

</html>