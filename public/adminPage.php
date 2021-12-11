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
                margin: 30px;
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
                } else if($flag == 3) {
            ?>
            <?php
                }
            ?>
        </main>

    </body>

</html>