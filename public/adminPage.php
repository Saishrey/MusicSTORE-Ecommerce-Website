<?php
    require "../private/autoload.php";

    //fetch customers from database
    $flag = 0;
    if(isset($_GET['admin_query'])) {
        if($_GET['admin_query'] == 'sellers') {
            $query = "select * from seller;";
            $stmnt = $con->prepare($query);
            $check = $stmnt->execute();
    
            if($check) {
                $seller_data = $stmnt->fetchAll(PDO::FETCH_OBJ);  //FETCH_ASSOC for array
            }
            $flag = 1;

        } else if($_GET['admin_query'] == 'instruments') {
            $query = "select * from instrument;";
            $stmnt = $con->prepare($query);
            $check = $stmnt->execute();
    
            if($check) {
                $inst_data = $stmnt->fetchAll(PDO::FETCH_OBJ);  //FETCH_ASSOC for array
            }
            $flag = 2;

        } else if($_GET['admin_query'] == 'orders') {
             // fetch all orders 

            $query_current_orders = "select count(*) as count from orders group by is_delivered having is_delivered = 0;";
            $stmnt_current_orders= $con->prepare($query_current_orders);
            $check_current_orders = $stmnt_current_orders->execute();
            if($check_current_orders) {
                $current_orders_array = $stmnt_current_orders->fetchAll(PDO::FETCH_OBJ);  //FETCH_ASSOC for array
            }

            $query_delivered_orders = "select count(*) as count from orders group by is_delivered having is_delivered = 1;";
            $stmnt_delivered_orders= $con->prepare($query_delivered_orders);
            $check_delivered_orders = $stmnt_delivered_orders->execute();
            if($check_delivered_orders) {
                $delivered_orders_array = $stmnt_delivered_orders->fetchAll(PDO::FETCH_OBJ);  //FETCH_ASSOC for array
            }

            $query_total_orders = "select count(*) as count from orders group by order_id;";
            $stmnt_total_orders= $con->prepare($query_total_orders);
            $check_total_orders = $stmnt_total_orders->execute();
            if($check_total_orders) {
                $total_orders_array = $stmnt_total_orders->fetchAll(PDO::FETCH_OBJ);  //FETCH_ASSOC for array
            }

            $query_orders = "select * from orders inner join ordered_instrument on orders.order_id = ordered_instrument.o_id order by is_delivered, order_date desc;";
            $stmnt_orders= $con->prepare($query_orders);
            $check_orders = $stmnt_orders->execute();

            if($check_orders) {
                $orders_array = $stmnt_orders->fetchAll(PDO::FETCH_OBJ);  //FETCH_ASSOC for array
            }

            $flag = 3;

        } else if($_GET['admin_query'] == 'agent') {
            $query = "select * from agent;";
            $stmnt = $con->prepare($query);
            $check = $stmnt->execute();
    
            if($check) {
                $agent_data = $stmnt->fetchAll(PDO::FETCH_OBJ);  //FETCH_ASSOC for array
            }
            $flag = 4;
        }
        
    } else if(isset($_GET['instrument_id'])) {
        //remove instrument from database
        $array['i_id'] = $_GET['instrument_id'];

        $inst_img = $_GET['instrument_img'];

        deleteProfilePic($inst_img);

        $query_del = "delete from instrument where inst_id=:i_id;";
        $stmnt_del = $con->prepare($query_del);
        $check_del = $stmnt_del->execute($array);

        header("Location: adminPage.php?admin_query=instruments");
        die;
    } else if(isset($_GET['agent_id'])) {
        //remove instrument from database
        $array['a_id'] = $_GET['agent_id'];

        $query_del = "delete from agent where agent_id=:a_id;";
        $stmnt_del = $con->prepare($query_del);
        $check_del = $stmnt_del->execute($array);

        header("Location: adminPage.php?admin_query=agent");
        die;
    } else if($_SERVER['REQUEST_METHOD'] == 'POST') { // Agent Data 
        $arr['agent_name'] = $_POST['agent_name'];
        $arr['agent_password'] = $_POST['agent_password'];
        $arr['agent_contact'] = $_POST['agent_contact'];
        $arr['agent_city'] = $_POST['agent_city'];
        $arr['agent_state'] = $_POST['agent_state'];
        $arr['agent_country'] = $_POST['agent_country'];
        $arr['agent_pin_code'] = $_POST['agent_pin_code'];
        $arr['agent_id'] = 'AGENT_'.strval(rand(100000,999999));

         $query = "insert into agent (agent_id, agent_name, agent_password, agent_contact, agent_city, agent_state, agent_country, agent_pin_code) values (:agent_id, :agent_name, :agent_password, :agent_contact, :agent_city, :agent_state, :agent_country, :agent_pin_code)";
         $stmnt = $con->prepare($query);
         $stmnt->execute($arr);

         echo "<script>
               alert('Agent data uploaded successfully.');
               window.location.replace('adminPage.php?admin_query=agent');
               </script>";

    } else {
        $query = "select * from customer where user_name <> 'admin';";
        $stmnt = $con->prepare($query);
        $check = $stmnt->execute();

        if($check) {
            $cust_data = $stmnt->fetchAll(PDO::FETCH_OBJ);  //FETCH_ASSOC for array
        }
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
        <title>Admin | MUSICSTORE</title>
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
                /* background: url(images/admin.jpg) no-repeat;
                background-size: cover; */
                flex-direction: column;
                height: 100%;
                width: 100%;
                /* left:0;
                right: 0;
                top: 0;
                bottom: 0;
                position: absolute; */
                background: linear-gradient(124deg, #ff2400, #e81d1d, #e8b71d, #e3e81d, #1de840, #1ddde8, #2b1de8, #dd00f3, #dd00f3);
                background-size: 1800% 1800%;

                -webkit-animation: rainbow 10s ease infinite;
                -z-animation: rainbow 10s ease infinite;
                -o-animation: rainbow 10s ease infinite;
                animation: rainbow 10s ease infinite;}
            }
            @-webkit-keyframes rainbow {
                0%{background-position:0% 82%}
                50%{background-position:100% 19%}
                100%{background-position:0% 82%}
            }
            @-moz-keyframes rainbow {
                0%{background-position:0% 82%}
                50%{background-position:100% 19%}
                100%{background-position:0% 82%}
            }
            @-o-keyframes rainbow {
                0%{background-position:0% 82%}
                50%{background-position:100% 19%}
                100%{background-position:0% 82%}
            }
            @keyframes rainbow { 
                0%{background-position:0% 82%}
                50%{background-position:100% 19%}
                100%{background-position:0% 82%}
            }
            header {
                /* background: #0082e6; */
                /* background: #181818; */
                background: rgba(24,24,24, 0.97);
                /* position: fixed; */
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
        </style>
        <header class="navbar-fixed"> 
        <input type="checkbox" id="check">
                <label for="check" class="checkbtn">
                    <i class="fa fa-bars"></i>
                </label>
                <a class="logo" href="adminPage.php">Music<span style="color:#1b9bff;">STORE</span>&trade;</a>
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
                width: 90%;
                background: white;
                min-width: 1400px;
                margin: auto;
                z-index: 2;
                border-radius: 6px;
                box-shadow: 0 7px 20px rgba(50, 50, 93, .2);
                padding-bottom: 80px;
            }
            .top-menu {
                width: 100%;
                border-radius: 6px 6px 0 0;
                /* background: rgb(5,29,54); */
                background: rgb(24,24,24);
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
                width: 20%;
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
        <main id="top">
            <?php
                if($flag == 0) { //Customers
            ?>
                <div class="user-info">
                    <div class="main-body">
                        <div class="data">
                            <div class="top-menu">
                                <div class="ul-div">
                                    <ul>
                                        <li><a class="active" href="adminPage.php">Cutomers</a></li>
                                        <li><a href="adminPage.php?admin_query=sellers">Sellers</a></li>
                                        <li><a href="adminPage.php?admin_query=instruments">Instruments</a></li>
                                        <li><a href="adminPage.php?admin_query=orders">Orders</a></li>
                                        <li><a href="adminPage.php?admin_query=agent">Agents</a></li>
                                    </ul>
                                </div>
                            </div>
                            <p class="inst-count">Customer count: <?=count($cust_data)?></p>
                            
                                <?php
                                    for($i = 0; $i < count($cust_data); $i++) {
                                ?>
                                <div class="inst-container">
                                    <div class="buy-cart-inst-img-cont">
                                        <div class="main-square-img">
                                            <?php
                                                if($cust_data[$i]->img_name== null) {
                                            ?>
                                            <img src="images/user-profile-img.png" alt="Image">

                                            <?php
                                                } else {
                                            ?>
                                            <img src="../private/uploads/<?=$cust_data[$i]->img_name?>" alt="Image">

                                            <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="inst-table">
                                        <table>
                                            <tr>
                                                <th>User ID</th>
                                                <th>User name</th>
                                                <th>Email</th>
                                                <th>Is a seller?</th>
                                            </tr>
                                            
                                            <tr>
                                                <td>
                                                    <?=$cust_data[$i]->user_id?>
                                                </td>
                                                <td>
                                                    <?=$cust_data[$i]->user_name?>
                                                </td>
                                                <td>
                                                    <?=$cust_data[$i]->email?>
                                                </td>
                                                <td>
                                                    <?php
                                                        if($cust_data[$i]->is_seller == 0 ) {
                                                    ?>
                                                    <p>No</p>
                                                    <?php
                                                        } else {
                                                    ?>
                                                    <p>Yes</p>
                                                    <?php
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    </div>
                                <?php
                                    }
                                ?>                                                   
                        </div>
                    </div>
                </div>
            <?php
                } else if($flag == 1) { //Sellers
            ?>
            <div class="user-info">
                    <div class="main-body">
                        <div class="data">
                            <div class="top-menu">
                                <div class="ul-div">
                                    <ul>
                                        <li><a href="adminPage.php">Cutomers</a></li>
                                        <li><a class="active"href="adminPage.php?admin_query=sellers">Sellers</a></li>
                                        <li><a href="adminPage.php?admin_query=instruments">Instruments</a></li>
                                        <li><a href="adminPage.php?admin_query=orders">Orders</a></li>
                                        <li><a href="adminPage.php?admin_query=agent">Agents</a></li>
                                    </ul>
                                </div>
                            </div>
                            <p class="inst-count">Seller count: <?=count($seller_data)?></p>
                            
                                <?php
                                    for($i = 0; $i < count($seller_data); $i++) {
                                ?>
                                <div class="inst-container">
                                    <div class="buy-cart-inst-img-cont">
                                        <div class="main-square-img">
                                            <?php
                                                if($seller_data[$i]->seller_dp == null) {
                                            ?>
                                            <img src="images/seller-dp.png" alt="Image">

                                            <?php
                                                } else {
                                            ?>
                                            <img src="../private/uploads/<?=$seller_data[$i]->seller_dp?>" alt="Image">

                                            <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="inst-table">
                                        <table>
                                            <tr>
                                                <th>Seller ID</th>
                                                <th>Company Name</th>
                                                <th>Seller Email</th>
                                                <th>Address</th>
                                                <th>Pin code</th>
                                                <th>Contact</th>
                                            </tr>
                                            
                                            <tr>
                                                <td>
                                                    <?=$seller_data[$i]->seller_id?>
                                                </td>
                                                <td>
                                                    <?=$seller_data[$i]->company_name?>
                                                </td>
                                                <td>
                                                    <?=$seller_data[$i]->seller_email?>
                                                </td>
                                                <td>
                                                    <?=$seller_data[$i]->seller_address?>
                                                </td>
                                                <td>
                                                    <?=$seller_data[$i]->seller_pin_code?>
                                                </td>
                                                <td>
                                                    <?=$seller_data[$i]->seller_contact?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    </div>
                                <?php
                                    }
                                ?>                           
                        </div>
                    </div>
                </div>
            <?php
                } else if($flag == 2) { //Instruments
            ?>
            <div class="user-info">
                    <div class="main-body">
                        <div class="data">
                            <div class="top-menu">
                                <div class="ul-div">
                                    <ul>
                                        <li><a href="adminPage.php">Cutomers</a></li>
                                        <li><a href="adminPage.php?admin_query=sellers">Sellers</a></li>
                                        <li><a class="active" href="adminPage.php?admin_query=instruments">Instruments</a></li>
                                        <li><a href="adminPage.php?admin_query=orders">Orders</a></li>
                                        <li><a href="adminPage.php?admin_query=agent">Agents</a></li>
                                    </ul>
                                </div>
                            </div>
                            <p class="inst-count">Instrument count: <?=count($inst_data)?></p>
                            
                                <?php
                                    for($i = 0; $i < count($inst_data); $i++) {
                                ?>
                                <div class="inst-container">
                                    <div class="buy-cart-inst-img-cont">
                                        <div class="main-square-img">
                                            <img src="../private/uploads/<?=$inst_data[$i]->inst_img?>" alt="Image">
                                        </div>
                                    </div>
                                    <div class="inst-table">
                                        <table>
                                            <tr>
                                                <th>Name</th>
                                                <th>ID</th>
                                                <th>Category</th>
                                                <th>Price</th>
                                                <th>Quantiy</th>
                                            </tr>
                                            
                                            <tr>
                                                <td>
                                                    <?=$inst_data[$i]->inst_name?>
                                                </td>
                                                <td>
                                                    <?=$inst_data[$i]->inst_id?>
                                                </td>
                                                <td>
                                                    <?=$inst_data[$i]->category?>
                                                </td>
                                                <td>
                                                    <?=$inst_data[$i]->price?>
                                                </td>
                                                <td>
                                                    <?=$inst_data[$i]->quantity?>
                                                </td>
                                            </tr>
                                        </table>
                                        <div class="rem-btn">
                                            <a class="remove-inst" href="adminPage.php?instrument_id=<?=$inst_data[$i]->inst_id?>&instrument_img=<?=$inst_data[$i]->inst_img?>">Remove Instrument</a>
                                        </div>
                                    </div>
                                    </div>
                                <?php
                                    }
                                ?>
                            
                        </div>
                    </div>
                </div>
            <?php
                } else if($flag == 3) { // Orders
            ?>
            <div class="user-info">
                <div class="main-body">
                    <div class="data">
                        <div class="top-menu">
                            <div class="ul-div">
                                <ul>
                                    <li><a href="adminPage.php">Cutomers</a></li>
                                    <li><a href="adminPage.php?admin_query=sellers">Sellers</a></li>
                                    <li><a href="adminPage.php?admin_query=instruments">Instruments</a></li>
                                    <li><a class="active" href="adminPage.php?admin_query=orders">Orders</a></li>
                                    <li><a href="adminPage.php?admin_query=agent">Agents</a></li>
                                </ul>
                            </div>
                        </div>
                        <style>
                            .orders {
                                padding: 40px 20px;
                            }
                            .orders-description {
                                display: flex;
                                flex-direction: row;
                                width: 90%;
                                margin: 20px auto;
                            }
                            .orders-description p {
                                width: 33%;
                                text-align: center;
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
                            <div class="orders-description">
                                <p>Total orders: <?=count($total_orders_array)?></p>
                                <p>Current orders: <?=$current_orders_array[0]->count?></p>
                                <p>Delivered: <?=$delivered_orders_array[0]->count?></p>
                            </div>
                            <?php
                                if(count($orders_array) > 0) {
                            ?>
                            <div class="orders-table-container">
                                <table>
                                    <tr>
                                        <th>Sr. No.</th>
                                        <th>Order ID</th>
                                        <th>Agent ID</th>
                                        <th>Customer ID</th>
                                        <th>Delivery address</th>
                                        <th>Instrument ID</th>
                                        <th>Order Date</th>
                                        <th>Delivery Date</th>
                                        <th>Delivered?</th>
                                        <th>Delivered date</th>
                                    </tr>
                                <?php
                                    for($i = 0; $i < count($orders_array); $i++) {
                                ?>
                                    <tr>
                                        <td><?=$i+1?></td>
                                        <td><?=$orders_array[$i]->order_id?></td>
                                        <td><?=$orders_array[$i]->delivery_agent_id?></td>
                                        <td><?=$orders_array[$i]->cust_id?></td>
                                        <td><?=$orders_array[$i]->delivery_address?></td>
                                        <td><?=$orders_array[$i]->i_id?></td>
                                        <td><?=$orders_array[$i]->order_date?></td>
                                        <td><?=$orders_array[$i]->delivery_date?></td>
                                        <?php
                                            if($orders_array[$i]->is_delivered == 1) {
                                        ?>
                                        <td>Yes</td>
                                        <?php
                                            } else {
                                        ?>
                                        <td>No</td>
                                        <?php
                                            }
                                        ?>
                                        <?php
                                            if($orders_array[$i]->delivered_date == null) {
                                        ?>
                                        <td>-</td>
                                        <?php
                                            } else {
                                        ?>
                                        <td><?=$orders_array[$i]->delivered_date?></td>
                                        <?php
                                            }
                                        ?>
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
            </div>
            <?php
                } else if($flag == 4) { // Agents
            ?>
            <div class="user-info">
                <div class="main-body">
                    <div class="data">
                        <div class="top-menu">
                            <div class="ul-div">
                                <ul>
                                    <li><a href="adminPage.php">Cutomers</a></li>
                                    <li><a href="adminPage.php?admin_query=sellers">Sellers</a></li>
                                    <li><a href="adminPage.php?admin_query=instruments">Instruments</a></li>
                                    <li><a href="adminPage.php?admin_query=orders">Orders</a></li>
                                    <li><a class="active"  href="adminPage.php?admin_query=agent">Agents</a></li>
                                </ul>
                            </div>
                        </div>
                        <?php
                            if(isset($_GET['uploadAgent']) && $_GET['uploadAgent']='true') {
                        ?>
                        <style>
                            .agent-upload form h2 {
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
                            .agent-upload form {
                                text-align: center;
                                width: 80%;
                                margin: auto;
                                padding-bottom: 40px;
                            }    
                            .agent-upload form .item {
                                width: 100%;
                            }
                            .agent-upload form > h3 {
                                font-size: 24px;
                                padding: 10px 5px;
                                color: rgb(96,108,138);
                                width: max-content;
                                position: relative;
                                bottom: 27px;
                                left: 50px;
                                background-color: rgb(239,243,246);
                            }
                            .submit-btn {
                                padding: 10px 40px;
                                width: 30%;
                                margin-top: 25px;
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
                            .smbt {
                                width: 50%;
                                margin: auto;
                                text-align: center;
                            }
                            .submit-btn:hover {
                                cursor: pointer;
                                color: white;
                                /* background-color: #195aaf; */
                                background: #369b22;
                                transition: 0.3s;
                            }
                        </style>
                            <div class="agent-upload">
                                <form action="adminPage.php" method="post">
                                    <h2>Delivery Agent Details</h2>
                                    <div class="item">
                                        <h3>Name:</h3>
                                        <input type="text" class="form-control" id="name" name="agent_name" placeholder="Name" required="required" maxlength="250" title="Name">
                                    </div>
                                    <div class="item">
                                        <h3>Password(PIN):</h3>
                                        <input type="password" class="form-control" id="password" name="agent_password" placeholder="4-Digit PIN" required="required" minlength="4" maxlength="4" title="PIN">
                                    </div>
                                    <div class="item">
                                        <h3>Contact:</h3>
                                        <input type="text" class="form-control" id="contact" name="agent_contact" placeholder="Contact" required="required" maxlength="10" title="Contact">
                                    </div>
                                    <div class="item">
                                        <h3>City:</h3>
                                        <input type="text" name="agent_city" id="city" class="form-control" placeholder="City" required="required" maxlength="100" title="City">
                                    </div>
                                    <div class="item">
                                        <h3>State:</h3>
                                        <select name="agent_state" id="state" class="form-control">
                                            <option value="Andhra Pradesh">Andhra Pradesh</option>
                                            <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
                                            <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                            <option value="Assam">Assam</option>
                                            <option value="Bihar">Bihar</option>
                                            <option value="Chandigarh">Chandigarh</option>
                                            <option value="Chhattisgarh">Chhattisgarh</option>
                                            <option value="Dadar and Nagar Haveli">Dadar and Nagar Haveli</option>
                                            <option value="Daman and Diu">Daman and Diu</option>
                                            <option value="Delhi">Delhi</option>
                                            <option value="Lakshadweep">Lakshadweep</option>
                                            <option value="Puducherry">Puducherry</option>
                                            <option value="Goa">Goa</option>
                                            <option value="Gujarat">Gujarat</option>
                                            <option value="Haryana">Haryana</option>
                                            <option value="Himachal Pradesh">Himachal Pradesh</option>
                                            <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                                            <option value="Jharkhand">Jharkhand</option>
                                            <option value="Karnataka">Karnataka</option>
                                            <option value="Kerala">Kerala</option>
                                            <option value="Madhya Pradesh">Madhya Pradesh</option>
                                            <option value="Maharashtra">Maharashtra</option>
                                            <option value="Manipur">Manipur</option>
                                            <option value="Meghalaya">Meghalaya</option>
                                            <option value="Mizoram">Mizoram</option>
                                            <option value="Nagaland">Nagaland</option>
                                            <option value="Odisha">Odisha</option>
                                            <option value="Punjab">Punjab</option>
                                            <option value="Rajasthan">Rajasthan</option>
                                            <option value="Sikkim">Sikkim</option>
                                            <option value="Tamil Nadu">Tamil Nadu</option>
                                            <option value="Telangana">Telangana</option>
                                            <option value="Tripura">Tripura</option>
                                            <option value="Uttar Pradesh">Uttar Pradesh</option>
                                            <option value="Uttarakhand">Uttarakhand</option>
                                            <option value="West Bengal">West Bengal</option>
                                        </select>
                                    </div>
                                    <div class="item">
                                        <h3>Country:</h3>
                                        <select name="agent_country" id="country" class="form-control">
                                            <option value="India">India</option>
                                        </select>
                                    </div>
                                    <div class="item">
                                        <h3>PIN Code:</h3>
                                        <input type="text" name="agent_pin_code" id="pin_code" class="form-control" placeholder="PIN Code" required="required" maxlength="6" title="Pin code">
                                    </div> 
                                    <div class="sbmt">
                                        <input type="submit" class="submit-btn" value="Confirm" name="confirm">
                                    </div>
                                </form>
                            </div>
                        <?php
                            } else {
                        ?>
                        <div class="inst-up-rem">
                                <a href="adminPage.php?admin_query=agent&uploadAgent=true" class="upload">Upload Delivery Agent Data</a>
                        </div>
                        <p class="inst-count">Agent count: <?=count($agent_data)?></p>
                            
                                <?php
                                    for($i = 0; $i < count($agent_data); $i++) {
                                ?>
                                <div class="inst-container">
                                    <div class="buy-cart-inst-img-cont">
                                        <div class="main-square-img">
                                            <img src="images/Delivery_agent.jpg" alt="Image">
                                        </div>
                                    </div>
                                    <div class="inst-table">
                                        <table>
                                            <tr>
                                                <th>Agent ID</th>
                                                <th>Name</th>
                                                <th>PIN</th>
                                                <th>Contact</th>
                                                <th>City</th>
                                                <th>State</th>
                                                <th>Country</th>
                                                <th>Zip Code</th>
                                            </tr>
                                            
                                            <tr>
                                                <td>
                                                    <?=$agent_data[$i]->agent_id?>
                                                </td>
                                                <td>
                                                    <?=$agent_data[$i]->agent_name?>
                                                </td>
                                                <td>
                                                    <?=$agent_data[$i]->agent_password?>
                                                </td>
                                                <td>
                                                    <?=$agent_data[$i]->agent_contact?>
                                                </td>
                                                <td>
                                                    <?=$agent_data[$i]->agent_city?>
                                                </td>
                                                <td>
                                                    <?=$agent_data[$i]->agent_state?>
                                                </td>
                                                <td>
                                                    <?=$agent_data[$i]->agent_country?>
                                                </td>
                                                <td>
                                                    <?=$agent_data[$i]->agent_pin_code?>
                                                </td>
                                            </tr>
                                        </table>
                                        <div class="rem-btn">
                                            <a class="remove-inst" href="adminPage.php?agent_id=<?=$agent_data[$i]->agent_id?>">Remove Agent</a>
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
            <?php
                }
            ?>
        </main>

    </body>

</html>