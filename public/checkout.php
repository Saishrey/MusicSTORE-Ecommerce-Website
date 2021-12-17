<?php 

    /*
        $_SESSION['checkout_inst_id']

        $_SESSION['checkout_total_price']
        $_SESSION['checkout_address_id']
        $_SESSION['address_string']
        $_SESSION['payment_method']
        $_SESSION['delivery_date']
        $_SESSION['delivery_agent_id']
        $_SESSION['checkout_seller_id']
        $_SESSION['delivery_agent_contact']
    */

    require "../private/autoload.php";

    $flag = 0;

    $contact = "";
    $locality = "";
    $city = "";
    $state = "";
    $country = "";
    $user_name = "";
    $pin_code = "";

    $error = False;
    $cart_order = False;

    if(isset($_POST['new_address']) && $_POST['new_address'] == "new_address") {
        // address
        // to check if address is <= 250
        $locality= $_POST['locality'];
        if(strlen($locality) > 250 && !$error) {
            echo "<script>
                  alert('Please refill the form. Locality length is maximum 250 characters.');
                  window.location.replace('checkout.php?add_address=true');
                  </script>"; 
            $error = True;
        }
        $locality = esc($locality);

        $city = $_POST['city'];
        if(strlen($city) > 250 && !$error) {
            echo "<script>
                  alert('Please refill the form. City length is maximum 100 characters.');
                  window.location.replace('checkout.php?add_address=true');
                  </script>"; 
            $error = True;
        }
        $city = esc($city);

        // to check if pincode is <= 6
        $pin_code = $_POST['pin_code'];
        if(strlen($pin_code) != 0 && strlen($pin_code) < 6 && !$error) {
            echo "<script>
                  alert('Please refill the form. Pincode must be 6 digits.');
                  window.location.replace('checkout.php?add_address=true');
                  </script>"; 
            $error = True;
        }

        $state = $_POST['state'];
        $country = $_POST['country'];

        //save to database
        $arr['user_id'] = $_SESSION['user_id'];
        $arr['locality'] = $locality;
        $arr['state'] = $state;
        $arr['country'] = $country;
        $arr['city'] = $city;
        $arr['pin_code'] = $pin_code;
        $query = "insert into address(c_id,locality,city,state,country,pin_code) values (:user_id,:locality,:city,:state,:country,:pin_code);";
        $stmnt = $con->prepare($query);
        $stmnt->execute($arr);

        echo "<script>
              alert('Address uploaded successfully.');
              window.location.replace('checkout.php?select_address=true');
              </script>";
    }

    if(isset($_GET['select_address']) && $_GET['select_address'] == 'true') {
        $arr['c_id'] = $_SESSION['user_id'];
        $query = "select * from address where c_id=:c_id";
        $stmnt = $con->prepare($query);
        $check = $stmnt->execute($arr);
    
        if($check) {
            $cust_address = $stmnt->fetchAll(PDO::FETCH_OBJ);  //FETCH_ASSOC for array
        }

    } 
    else if(isset($_GET['add_address']) && $_GET['add_address'] == 'true') {

        $flag = 1;

    } 
    else if(isset($_POST['address_selected']) && $_POST['address_selected'] == 'true') {
        $_SESSION['checkout_address_id'] = $_POST['address_id'];   // Store the selected address in SESSION

        $arr['add_id'] = $_SESSION['checkout_address_id'];
        $query = "select * from address where address_id=:add_id;";
        $stmnt = $con->prepare($query);
        $check = $stmnt->execute($arr);
    
        if($check) {
            $add_arr = $stmnt->fetchAll(PDO::FETCH_OBJ);  //FETCH_ASSOC for array
            $add_arr = $add_arr[0];

            // to check if there is a delivery agent for that state

            $agent_add['state'] = $add_arr->state;
            $query_agent =  "select * from agent where agent_state=:state order by rand() limit 1;";
            $stmnt_agent = $con->prepare($query_agent);
            $check_agent = $stmnt_agent->execute($agent_add);

            if($check_agent) {
                $add_agent = $stmnt_agent->fetchAll(PDO::FETCH_OBJ);  //FETCH_ASSOC for array

                if(is_array($add_agent) && count($add_agent) > 0) {
                    $_SESSION['delivery_agent_id'] = $add_agent[0]->agent_id;
                    $_SESSION['delivery_agent_contact'] = $add_agent[0]->agent_contact;
                }
                else {
                    echo "<script>
                            alert('Delivery not available for this address');
                            window.location.replace('checkout.php?select_address=true');
                        </script>";

                    $flag = 1;
                }
            }
            else {
                echo "<script>
                            alert('Delivery not available for this address');
                            window.location.replace('checkout.php?select_address=true');
                        </script>";
                $flag = 1;
                
            }
        
            $address = "";
            $address .= $add_arr->locality.", ";
            $address .= $add_arr->city."-";
            $address .= $add_arr->state." ";
            $address .= $add_arr->pin_code;

            $_SESSION['address_string'] = $address;
        }

        $flag = 2;
    } 
    else if (isset($_GET['select_payment']) && $_GET['select_payment'] == 'true') {
        $flag = 2;
    }
    else if(isset($_POST['payment_method_selected']) && $_POST['payment_method_selected'] == 'true') {
        $_SESSION['payment_method'] = $_POST['payment_method'];

        if(isset($_SESSION['checkout_inst_id'])) {
            $arr['i_id'] = $_SESSION['checkout_inst_id'];
            $query = "select * from instrument where inst_id=:i_id;";
            $stmnt = $con->prepare($query);
            $check = $stmnt->execute($arr);

            if($check) {
                $inst_data = $stmnt->fetchAll(PDO::FETCH_OBJ);  //FETCH_ASSOC for array
            }
        } else {
            $cart_order = True;
            $arr['user_id'] = $_SESSION['user_id'];
            $query = "select * from instrument where inst_id in (select instrument_id from cart where customer_id = :user_id);";
            $stmnt = $con->prepare($query);
            $check = $stmnt->execute($arr);

            if($check) {
                $cart_inst_data = $stmnt->fetchAll(PDO::FETCH_OBJ);  //FETCH_ASSOC for array
            }
        }
        $flag = 3;
    }
    else if(isset($_GET['place_order']) && $_GET['place_order'] == 'true') {

        // write some code 
        // ...
        $order_id = "ORDER_".get_random_string(30);

        $arr_order['order_id'] = $order_id;
        $arr_order['cust_id'] =  $_SESSION['user_id'];
        $arr_order['delivery_agent_id'] =  $_SESSION['delivery_agent_id'];
        $arr_order['delivery_date'] =  $_SESSION['delivery_date'];
        $arr_order['delivery_address'] = $_SESSION['address_string'];
        $arr_order['total_amount'] =  $_SESSION['checkout_total_price'];
        $arr_order['payment_method'] =  $_SESSION['payment_method'];

        $query_order = "insert into orders(order_id,cust_id,delivery_agent_id,delivery_address,delivery_date,total_amount,payment_method) values(:order_id,:cust_id,:delivery_agent_id,:delivery_address,:delivery_date,:total_amount,:payment_method);";
        $stmnt_order = $con->prepare($query_order);
        $check_order = $stmnt_order->execute($arr_order);

        if(isset($_SESSION['checkout_inst_id'])) {
            // to fetch inst data
            $arr['i_id'] = $_SESSION['checkout_inst_id'];
            $query = "select * from instrument where inst_id=:i_id;";
            $stmnt = $con->prepare($query);
            $check = $stmnt->execute($arr);

            if($check) {
                $inst_data = $stmnt->fetchAll(PDO::FETCH_OBJ);  //FETCH_ASSOC for array
            }

            $arr_ordered_inst['o_id'] = $order_id;
            $arr_ordered_inst['i_id'] =  $_SESSION['checkout_inst_id'];
            $arr_ordered_inst['s_id'] = $_SESSION['checkout_seller_id'];
            $arr_ordered_inst['i_name'] = $inst_data[0]->inst_name;
            $arr_ordered_inst['i_brand_name'] = $inst_data[0]->brand_name;
            $arr_ordered_inst['i_price'] = $inst_data[0]->price;
            $arr_ordered_inst['i_img_name'] = $inst_data[0]->inst_img;
            $arr_ordered_inst['i_category'] = $inst_data[0]->category;

            $arr_ordered_inst['i_quantity'] = 1;

            $query_ordered_inst = "insert into ordered_instrument(o_id,i_id,s_id,i_name,i_brand_name,i_category,i_price,i_img_name,i_quantity) values(:o_id,:i_id,:s_id,:i_name,:i_brand_name,:i_category,:i_price,:i_img_name,:i_quantity);";
            $stmnt_ordered_inst = $con->prepare($query_ordered_inst);
            $check_ordered_inst = $stmnt_ordered_inst->execute($arr_ordered_inst);

            // reducing quantity of existing instruments
            $arr_dec['inst_id'] = $_SESSION['checkout_inst_id'];
            $query_dec = "update instrument set quantity = greatest(0 , quantity-1) where inst_id=:inst_id;";
            $stmnt_dec = $con->prepare($query_dec);
            $check_dec = $stmnt_dec->execute($arr_dec);
        } else {
            // ordered from cart
            $arr_cart['customer_id'] = $_SESSION['user_id'];
            $query_cart = "select * from instrument where inst_id in (select instrument_id from cart where customer_id = :customer_id);";
            $stmnt_cart = $con->prepare($query_cart);
            $check_cart = $stmnt_cart->execute($arr_cart);

            if($check_cart) {
                $inst_data = $stmnt_cart->fetchAll(PDO::FETCH_OBJ);  //FETCH_ASSOC for array
            }

            // insert into ordered instruments 
            $arr_ordered_inst['o_id'] = $order_id;
            $arr_ordered_inst['i_quantity'] = 1;

            for($i = 0; $i < count($inst_data); $i++) {
                $arr_ordered_inst['i_id'] = $inst_data[$i]->inst_id;
                $arr_ordered_inst['s_id'] = $inst_data[$i]->s_id;
                $arr_ordered_inst['i_name'] = $inst_data[$i]->inst_name;
                $arr_ordered_inst['i_brand_name'] = $inst_data[$i]->brand_name;
                $arr_ordered_inst['i_price'] = $inst_data[$i]->price;
                $arr_ordered_inst['i_img_name'] = $inst_data[$i]->inst_img;
                $arr_ordered_inst['i_category'] = $inst_data[$i]->category;

                $query_ordered_inst = "insert into ordered_instrument(o_id,i_id,s_id,i_name,i_brand_name,i_category,i_price,i_img_name,i_quantity) values(:o_id,:i_id,:s_id,:i_name,:i_brand_name,:i_category,:i_price,:i_img_name,:i_quantity);";
                $stmnt_ordered_inst = $con->prepare($query_ordered_inst);
                $check_ordered_inst = $stmnt_ordered_inst->execute($arr_ordered_inst);
                // reducing quantity of existing instruments
                $arr_dec['inst_id'] = $inst_data[$i]->inst_id;
                $query_dec = "update instrument set quantity = greatest(0 , quantity-1) where inst_id=:inst_id;";
                $stmnt_dec = $con->prepare($query_dec);
                $check_dec = $stmnt_dec->execute($arr_dec);
            }

            // delete instruments from cart
            $arr_empty_cart['c_id'] = $_SESSION['user_id'];
            $query_empty_cart = "DELETE FROM cart where customer_id=:c_id;";
            $stmnt_empty_cart = $con->prepare($query_empty_cart);
            $check_empty_cart = $stmnt_empty_cart->execute($arr_empty_cart);
        }   
        //send email
        sendOrderPlacedEmail($_SESSION['email'], $order_id, $_SESSION['delivery_date'], $_SESSION['delivery_agent_contact']);
        // show order successful

        if(isset($_SESSION['checkout_inst_id'])) {
            unset($_SESSION['checkout_inst_id']);
        }
        unset($_SESSION['checkout_total_price']);
        unset($_SESSION['checkout_address_id']);
        unset($_SESSION['address_string']);
        unset($_SESSION['payment_method']);
        unset($_SESSION['delivery_date']);
        unset($_SESSION['delivery_agent_id']);
        unset($_SESSION['checkout_seller_id']);
        unset($_SESSION['delivery_agent_contact']);

        $flag = 4;
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
        <title> Checkout | MusicSTORE</title>
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
                /* background: rgb(24,24,24); */
                background-color: rgb(239,243,246);
                font-family: 'Montserrat', sans-serif;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                min-width: 1630px;
            }
            header {
                /* background: #0082e6; */
                /* background: #181818; */
                background: rgba(24,24,24, 0.97);
                /* background: rgba(9,33,51, 0.97); */
                /* background: rgb(24,24,24); */
                height: 80px;
                width: 100%;
                border-bottom: 1px solid #1b9bff;
                position: fixed;
                z-index: 3;
            }
            header > p {
                text-align: center;
            }
            .logo {
                color: white;
                font-size: 35px;
                line-height: 80px;
                /* margin-left: 100px; */
                font-weight: bold;
                text-decoration: none;
                transition: font-size 0.2s;
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
                padding: 80px 0;
            }
            
            main .main-body {
                /* background: linear-gradient(whitesmoke, #E6E6E6); */
                padding: 80px 20px 0 20px;
                display: flex;
                flex-direction: column;
                /* padding-bottom: 80px; */
            }
            .data {
                width: 70%;
                /* background: rgb(48,49,52); */
                background-color: rgb(239,243,246);
                min-width: 625px;
                margin: auto;
                z-index: 2;
                border-radius: 6px;
                box-shadow: 0 7px 20px rgba(50, 50, 93, .2);
            }
            .top-menu {
                width: 100%;
                background: #0F3460;
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
                width: 100%;
                margin: auto;
            }
            .top-menu ul li {
                width: 33%;
            }
            .top-menu ul li p {
                width: 90%;
                margin: auto;
                text-decoration: none;
                padding: 10px 30px;
                color: #969BA1;
            }
            .top-menu ul li .active {
                color: #1b9bff;
                border-bottom: 2px solid white;
            }
            .data .account-details {
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
            .data .account-details > form {
                width: 100%;
            }
            .data:hover {
                box-shadow: 0 10px 30px rgba(50, 50, 93, .2);
                transition: 500ms;
            }
            .data .account-details h2 {
                font-size: 3rem;
                margin-bottom: 40px;
                /* color: black; */
                /* color: white; */
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
            .address {
                margin-top: 50px;
                width: 100%;
            }  
            .address form {
                text-align: center;
                width: 80%;
                margin: auto;
                padding-bottom: 40px;
                border: 2px solid #1b9bff;
                border-radius: 6px;
            }    
            .address form .item {
                width: 100%;
            }
            .address form > h3 {
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
                font-family: 'Montserrat', sans-serif;
                background: none;
                outline: none;
                width: 50%;
                margin: auto;
                /* margin-top: 20px;
                margin-bottom: 20px; */
                border: none;
                font-size: 20px;
                text-align: center;
                color: purple;
            }
            .smbt {
                width: 50%;
                margin: auto;
                text-align: center;
            }
            .submit-btn:hover {
                cursor: pointer;
                color: #1b9bff;
                transition: 250ms linear;
            }
            .add-address {
                width: 50%;
                margin: 20px auto;
                text-align: center;
            }
            .add-address a {
                font-family: 'Montserrat', sans-serif;
                background: none;
                outline: none;    
                border: none;
                text-decoration: none;
                font-size: 20px;
                text-align: center;
                color: purple;
            }
            .add-address a:hover {
                cursor: pointer;
                color: #1b9bff;
                transition: 250ms linear;
            }
            .hidden_input {
                display: none;
            }
        </style>
            <header class="myheader" id="header">
                <!-- <input type="checkbox" id="check">
                <label for="check" class="checkbtn">
                    <i class="fa fa-bars"></i>
                </label> -->
                <p><a class="logo" href="index.php">Music<span style="color:#1b9bff;">STORE</span>&trade;<sub style="font-size: 18px;">Checkout</sub></a></p>
            </header>
        <?php
            if($flag == 0) { // select address
        ?>
        <main id="top">
                <div class="main-body">
                    <div class="data">
                        <div class="top-menu">
                            <div class="ul-div">
                                <ul>
                                    <li><p class="active">1. Delivery Address</p></li>
                                    <li><p title="Finish step 1">2. Payment method</a></li>
                                    <li><p title="Finish step 1 & 2" >3. Place Order</a></li>
                                </ul>
                            </div>
                        </div>
                        <style>
                            .order-summary {
                                display: flex;
                                flex-direction: row;
                                width: 80%;
                                margin: 40px auto;
                                border-radius: 6px;
                                padding: 10px;
                                box-shadow: white -10px -10px 20px 5px, rgb(24, 24, 24, 0.2) 10px 10px 20px 5px ;
                            }
                            .order-summary h2 {
                                margin: 20px 0;
                                padding: 5px 40px;
                                text-align: left;
                                color: rgb(50,50,93);
                                width: 40%;
                            }
                            .order-summary .price-desc {
                                width: 60%;
                                margin: 20px 0;
                                color: grey;
                                font-size: 18px;
                                padding: 5px 40px 5px 80px;
                                text-align: right;
                            }
                            .price-desc .price {
                                display: flex;
                                flex-direction: row;
                                padding: 10px;
                            }
                            .left, .right {
                                width: 50%;
                            }
                        </style>
                        <div class="order-summary">
                            <h2>Order summary</h2>
                            <div class="price-desc">
                                <div class="price">
                                    <p class="left">Instruments:</p>
                                    <p class="right">&#8377; <?=$_SESSION['checkout_total_price']?></p>
                                </div>
                                <div class="price">
                                    <p class="left">Delivery:</p>
                                    <p class="right">&#8377; 0</p>
                                </div>
                                <hr>
                                <div class="price">
                                    <p class="left" style="font-size: 24px; color: red;">Order Total:</p>
                                    <p class="right" style="font-size: 24px; color: red;">&#8377; <?=$_SESSION['checkout_total_price']?></p>
                                </div>
                            </div>
                        </div>
                        <style>
                            .add-item {
                                width: 80%;
                                /* margin: auto; */
                                margin: 10px auto;
                                display: flex;
                                font-size: 20px;
                                flex-direction: row;
                            }
                            .add-item .custom-radio {
                                width: 20%;
                                text-align: right;
                                padding: 20px;
                            }
                            .add-item label {
                                cursor: pointer;
                                padding: 20px;
                                width: 80%;
                            }
                            .address-submit-btn {
                                font-family: 'Montserrat', sans-serif;
                                background: #F3950D;
                                outline: none;
                                width: 30%;
                                border: none;
                                margin: 10px auto;
                                padding: 10px;
                                border-radius: 6px;
                                font-size: 18px;
                                text-align: center;
                                color: white;
                            }
                            .address-submit-btn:hover {
                                cursor: pointer;
                                background: rgb(187,103,54);
                                transition: 250ms linear;
                            }
                        </style>
                        <div class="account-details">
                            <h2>Select Delivery Address</h2>
                            <div class="address">
                                <form action="checkout.php" method="post">
                                    <h3>My addresses</h3>
                                    <?php
                                        for($i = 0; $i < count($cust_address); $i++) {
                                    ?>
                                    <div class="add-item">
                                        <p class="custom-radio"><input type="radio"  id="address<?=strval($i)?>" name="address_id" value="<?=$cust_address[$i]->address_id?>" <?php if($i == 0)  { ?> checked <?php } ?>></p>
                                        <?php
                                            $address = "";
                                            $address .= $cust_address[$i]->locality.", ";
                                            $address .= $cust_address[$i]->city."-";
                                            $address .= $cust_address[$i]->state." ";
                                            $address .= $cust_address[$i]->pin_code;
                                        ?>
                                        <label for="address<?=strval($i)?>"><p class="fix-email" style="color:#grey; text-align: left;" id="email" ><?=$address?></p></label>
                                    </div>
                                    <?php
                                        }
                                    ?>
                                    <div class="sbmt">
                                        <input type="text" class="hidden_input" name="address_selected" value="true">
                                        <input class="address-submit-btn" type="submit" value="Use this address">
                                    </div>
                                </form>
                                <p class="add-address"><a href="checkout.php?add_address=true"><i class="fa fa-plus"></i> Add address</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        <?php
            } else if($flag == 1) { // add new address
        ?>
        <main id="top">
                <div class="main-body">
                    <div class="data">
                        <div class="top-menu">
                            <div class="ul-div">
                                <ul>
                                    <li><p class="active">1. Delivery Address</p></li>
                                    <li><p title="Finish step 1">2. Payment method</a></li>
                                    <li><p title="Finish step 1 & 2" >3. Place Order</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="account-details">
                            <div class="address">
                                <form action="checkout.php" method="post">
                                    <h3>New Address</h3>
                                    <div class="item">
                                        <h3>Locality:</h3>
                                        <input type="text" class="form-control" id="locality" name="locality" placeholder="Locality" required="required" maxlength="250" title="Locality">
                                    </div>
                                    <div class="item">
                                        <h3>City:</h3>
                                        <input type="text" name="city" id="city" class="form-control" placeholder="City" required="required" maxlength="100" title="City">
                                    </div>
                                    <div class="item">
                                        <h3>State:</h3>
                                        <select name="state" id="state" class="form-control">
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
                                        <select name="country" id="country" class="form-control">
                                            <option value="India">India</option>
                                        </select>
                                    </div>
                                    <div class="item">
                                        <h3>PIN Code:</h3>
                                        <input type="text" name="pin_code" id="pin_code" class="form-control" placeholder="PIN Code" required="required" maxlength="6" title="Pin code">
                                    </div> 
                                    <div class="sbmt">
                                        <input type="text" class="hidden_input" name="new_address" value="new_address">
                                        <input type="submit" class="submit-btn" value="Confirm" name="confirm">
                                    </div>
                                </form>
                                <p class="add-address"><a href="checkout.php?select_address=true">Cancel</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
                       
        
        
        
        <?php
            } else if($flag == 2) { // payment
        ?>
        <main id="top">
                <div class="main-body">
                    <div class="data">
                        <div class="top-menu">
                            <div class="ul-div">
                                <ul>
                                    <li><p style="color: #6ECB63;" >1. Delivery Address <i class="fa fa-check"></i></p></li>
                                    <li><p class="active">2. Payment method</a></li>
                                    <li><p title="Finish step2" >3. Place Order</a></li>
                                </ul>
                            </div>
                        </div>
                        <style>
                            .order-summary {
                                display: flex;
                                flex-direction: row;
                                width: 80%;
                                margin: 40px auto;
                                border-radius: 6px;
                                padding: 10px;
                                box-shadow: white -10px -10px 20px 5px, rgb(24, 24, 24, 0.2) 10px 10px 20px 5px ;
                            }
                            .order-summary h2 {
                                margin: 20px 0;
                                padding: 5px 40px;
                                text-align: left;
                                color: rgb(50,50,93);
                                width: 40%;
                            }
                            .order-summary .price-desc {
                                width: 60%;
                                margin: 20px 0;
                                color: grey;
                                font-size: 18px;
                                padding: 5px 40px 5px 80px;
                                text-align: right;
                            }
                            .price-desc .price {
                                display: flex;
                                flex-direction: row;
                                padding: 10px;
                            }
                            .left, .right {
                                width: 50%;
                            }
                        </style>
                        <div class="order-summary">
                            <h2>Order summary</h2>
                            <div class="price-desc">
                                <div class="price">
                                    <p class="left">Instruments:</p>
                                    <p class="right">&#8377; <?=$_SESSION['checkout_total_price']?></p>
                                </div>
                                <div class="price">
                                    <p class="left">Delivery:</p>
                                    <p class="right">&#8377; 0</p>
                                </div>
                                <hr>
                                <div class="price">
                                    <p class="left" style="font-size: 24px; color: red;">Order Total:</p>
                                    <p class="right" style="font-size: 24px; color: red;">&#8377; <?=$_SESSION['checkout_total_price']?></p>
                                </div>
                            </div>
                        </div>
                        <style>
                            .selected-address-div {
                                display: flex;
                                flex-direction: row;
                                width: 80%;
                                margin: 40px auto;
                                border-radius: 6px;
                                padding: 10px;
                                box-shadow: white -10px -10px 20px 5px, rgb(24, 24, 24, 0.2) 10px 10px 20px 5px ;
                            }
                            .selected-address-div:hover {
                                background: #B8E4F0;
                                transition: 250ms linear;
                            }
                            .selected-address-div h2 {
                                margin: 20px 0;
                                text-align: left;
                                padding-left: 40px;
                                color: rgb(50,50,93);
                                width: 30%;
                            }
                            .selected-address-div p {
                                margin: 20px 0;
                                color: grey;
                                font-size: 18px;
                                padding: 5px 30px;
                                text-align: center;
                                width: 50%;
                            }
                            .selected-address-div .add-address {
                                width: 20%;
                            }
                        </style>
                        <div class="selected-address-div">
                            <h2>1. Delivery Address</h2>
                            <p><?=$_SESSION['address_string']?></p>
                            <p class="add-address"><a href="checkout.php?select_address=true"> Change</a></p>
                        </div>
                        <style>
                            .add-item {
                                width: 80%;
                                /* margin: auto; */
                                margin: 10px auto;
                                display: flex;
                                font-size: 20px;
                                flex-direction: row;
                            }
                            .add-item .custom-radio {
                                width: 20%;
                                text-align: right;
                                padding: 20px;
                            }
                            .add-item label {
                                cursor: pointer;
                                padding: 20px;
                                width: 80%;
                            }
                            .address-submit-btn {
                                font-family: 'Montserrat', sans-serif;
                                background: #F3950D;
                                outline: none;
                                width: 30%;
                                border: none;
                                margin: 10px auto;
                                padding: 10px;
                                border-radius: 6px;
                                font-size: 18px;
                                text-align: center;
                                color: white;
                            }
                            .address-submit-btn:hover {
                                cursor: pointer;
                                background: rgb(187,103,54);
                                transition: 250ms linear;
                            }
                        </style>
                        <div class="account-details">
                            <h2>Select Payment Method</h2>
                            <div class="address">
                                <form action="checkout.php" method="post">
                                    <h3>Payment Methods</h3>
                                    <div class="add-item">
                                        <p class="custom-radio"><input type="radio"  id="p1" name="payment_method" value="Debit/Credit/ATM Card" checked></p>
                                        <label for="p1"><p class="fix-email" style="color:#grey; text-align: left;" >Debit/Credit/ATM Card</p></label>
                                    </div>
                                    <div class="add-item">
                                        <p class="custom-radio"><input type="radio"  id="p2" name="payment_method" value="Net Banking" ></p>
                                        <label for="p2"><p class="fix-email" style="color:#grey; text-align: left;" >Net Banking</p></label>
                                    </div>
                                    <div class="add-item">
                                        <p class="custom-radio"><input type="radio"  id="p3" name="payment_method" value="Other UPI Apps" ></p>
                                        <label for="p3"><p class="fix-email" style="color:#grey; text-align: left;" >Other UPI Apps</p></label>
                                    </div>
                                    <div class="add-item">
                                        <p class="custom-radio"><input type="radio"  id="p4" name="payment_method" value="EMI" ></p>
                                        <label for="p4"><p class="fix-email" style="color:#grey; text-align: left;" >EMI</p></label>
                                    </div>
                                    <div class="add-item">
                                        <p class="custom-radio"><input type="radio"  id="p5" name="payment_method" value="Pay on delivery" ></p>
                                        <label for="p5"><p class="fix-email" style="color:#grey; text-align: left;" >Pay on delivery</p></label>
                                    </div>
                                    <div class="sbmt">
                                        <input type="text" class="hidden_input" name="payment_method_selected" value="true">
                                        <input class="address-submit-btn" type="submit" value="Use this payment method">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        </main>
        <?php
            } else if($flag == 3) { // place order
        ?>
        <main id="top">
                <div class="main-body">
                    <div class="data">
                        <div class="top-menu">
                            <div class="ul-div">
                                <ul>
                                    <li><p style="color: #6ECB63;" >1. Delivery Address <i class="fa fa-check"></i></p></li>
                                    <li><p  style="color: #6ECB63;" >2. Payment method  <i class="fa fa-check"></i></a></li>
                                    <li><p class="active" >3. Place Order</a></li>
                                </ul>
                            </div>
                        </div>
                        <style>
                            .order-summary {
                                display: flex;
                                flex-direction: row;
                                width: 80%;
                                margin: 40px auto;
                                border-radius: 6px;
                                padding: 10px;
                                box-shadow: white -10px -10px 20px 5px, rgb(24, 24, 24, 0.2) 10px 10px 20px 5px ;
                            }
                            .order-summary h2 {
                                margin: 20px 0;
                                padding: 5px 40px;
                                text-align: left;
                                color: rgb(50,50,93);
                                width: 40%;
                            }
                            .order-summary .price-desc {
                                width: 60%;
                                margin: 20px 0;
                                color: grey;
                                font-size: 18px;
                                padding: 5px 40px 5px 80px;
                                text-align: right;
                            }
                            .price-desc .price {
                                display: flex;
                                flex-direction: row;
                                padding: 10px;
                            }
                            .left, .right {
                                width: 50%;
                            }
                        </style>
                        <div class="order-summary">
                            <h2>Order summary</h2>
                            <div class="price-desc">
                                <div class="price">
                                    <p class="left">Instruments:</p>
                                    <p class="right">&#8377; <?=$_SESSION['checkout_total_price']?></p>
                                </div>
                                <div class="price">
                                    <p class="left">Delivery:</p>
                                    <p class="right">&#8377; 0</p>
                                </div>
                                <hr>
                                <div class="price">
                                    <p class="left" style="font-size: 24px; color: red;">Order Total:</p>
                                    <p class="right" style="font-size: 24px; color: red;">&#8377; <?=$_SESSION['checkout_total_price']?></p>
                                </div>
                            </div>
                        </div>
                        <style>
                            .selected-address-div {
                                display: flex;
                                flex-direction: row;
                                width: 80%;
                                margin: 40px auto;
                                border-radius: 6px;
                                padding: 10px;
                                box-shadow: white -10px -10px 20px 5px, rgb(24, 24, 24, 0.2) 10px 10px 20px 5px ;
                            }
                            .selected-address-div:hover {
                                background: #B8E4F0;
                                transition: 250ms linear;
                            }
                            .selected-address-div h2 {
                                margin: 20px 0;
                                text-align: left;
                                padding-left: 40px;
                                color: rgb(50,50,93);
                                width: 30%;
                            }
                            .selected-address-div p {
                                margin: 20px 0;
                                color: grey;
                                font-size: 18px;
                                padding: 5px 30px;
                                text-align: center;
                                width: 50%;
                            }
                            .selected-address-div .add-address {
                                width: 20%;
                            }
                        </style>
                        <div class="selected-address-div">
                            <h2>1. Delivery Address</h2>
                            <p><?=$_SESSION['address_string']?></p>
                            <p class="add-address"><a href="checkout.php?select_address=true"> Change</a></p>
                        </div>
                        <div class="selected-address-div">
                            <h2>2. Payment Method</h2>
                            <p><?=$_SESSION['payment_method']?></p>
                            <p class="add-address"><a href="checkout.php?select_payment=true"> Change</a></p>
                        </div>
                        <style>
                            .place-order {
                                padding: 10px;
                                width: 80%;
                                box-shadow: white -10px -10px 20px 5px, rgb(24, 24, 24, 0.2) 10px 10px 20px 5px ;
                                border-radius: 6px;
                                margin: 40px auto;
                            }
                            .place-order > h2 {
                                margin: 20px 0;
                                text-align: left;
                                padding-left: 40px;
                                color: rgb(50,50,93);
                                width: 30%;
                            }
                            .product-item {
                                display: flex;
                                flex-direction: row;
                                width: 80%;
                                margin: 10px auto;
                                border: 2px solid #1b9bff;
                                border-radius: 6px;
                            }
                            /* .place-order:hover {
                                background: #B8E4F0;
                                transition: 250ms linear;
                            } */
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
                                margin: auto;
                                display: block;
                                font-size: 18px;
                            }
                            .sub-div > ul li {
                                padding: 10px;
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
                            .place-order .price {
                                display: flex;
                                flex-direction: row;
                                width: 60%;
                                margin: 15px auto;
                                padding: 10px;
                            }
                            .place-order .price .left, .place-order .price .right {
                                text-align: center;
                                width: 50%;
                            }
                            .delivery-date {
                                padding: 30px 5px;
                                text-align: center;
                            }
                            .delivery-date h2 {
                                font-weight: 100;
                            }
                            .place-order-btn {
                                width: 100%;
                                border: none;
                                margin: 10px auto;
                            }
                            .place-order-btn p {
                                width: 60%;
                                padding: 30px 0;
                                margin: auto;
                                text-align: center;
                            }
                            .place-order-btn p .p-o-b {
                                font-family: 'Montserrat', sans-serif;
                                background: #F3950D;
                                outline: none;
                                padding: 15px 80px;
                                text-decoration: none;
                                border-radius: 6px;
                                font-size: 18px;
                                text-align: center;
                                color: white;
                            }
                            .place-order-btn p .p-o-b:hover {
                                cursor: pointer;
                                background: rgb(187,103,54);
                                transition: 250ms linear;
                            }
                        </style>
                        <div class="place-order">
                            <h2>3. Place Order</h2>
                            <?php
                                $date = strtotime("+7 day");
                                $delivery_date = date('F j, Y', $date);
                                $_SESSION['delivery_date'] = $delivery_date;
                            ?>
                            <div class="delivery-date">
                                <h2>Delivery date: <span style="color: #4E9F3D;"><?=$delivery_date?></span></h2>
                            </div>
                            <?php
                                if($cart_order) {
                                    for($i = 0; $i < count($cart_inst_data); $i++) {
                            ?>
                            <div class="product-item">    
                                <div class="buy-cart-inst-img-cont">
                                    <div class="main-square-img">
                                        <img src="../private/uploads/<?=$cart_inst_data[$i]->inst_img?>" alt="Image">
                                    </div>
                                </div>
                                <div class="inst-details-buy-cart">
                                    <h1 style="text-transform: capitalize;"><?=$cart_inst_data[$i]->inst_name?></h1>
                                    <h3 class="sold-by">Sold by: <?=$cart_inst_data[$i]->brand_name?></h3>     
                                    <div class="price-and-buy">
                                        <div class="sub-div">
                                            <h2>Price: <span style="color: red;">&#8377; <?=$cart_inst_data[$i]->price?></span> </h2>
                                            <ul>
                                                <li><p>1 year warranty</p></li>
                                                <li> <p>MusicSTORE assured <i class="fa fa-check-circle" aria-hidden="true"></i></p> </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>   
                            </div>
                            <?php
                                    }
                                } else {
                            ?>
                            <div class="product-item">    
                                <div class="buy-cart-inst-img-cont">
                                    <div class="main-square-img">
                                        <img src="../private/uploads/<?=$inst_data[0]->inst_img?>" alt="Image">
                                    </div>
                                </div>
                                <div class="inst-details-buy-cart">
                                    <h1 style="text-transform: capitalize;"><?=$inst_data[0]->inst_name?></h1>
                                    <h3 class="sold-by">Sold by: <?=$inst_data[0]->brand_name?></h3>     
                                    <div class="price-and-buy">
                                        <div class="sub-div">
                                            <h2>Price: <span style="color: red;">&#8377; <?=$inst_data[0]->price?></span> </h2>
                                            <ul>
                                                <li><p>1 year warranty</p></li>
                                                <li> <p>MusicSTORE assured <i class="fa fa-check-circle" aria-hidden="true"></i></p> </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>   
                            </div>
                            <?php
                                }
                            ?>
                            <hr>
                            <div class="price">
                                <p class="left" style="font-size: 24px; color: red;">Order Total:</p>
                                <p class="right" style="font-size: 24px; color: red;">&#8377; <?=$_SESSION['checkout_total_price']?></p>
                            </div>
                            <div class="place-order-btn">
                                <p><a class="p-o-b" href="checkout.php?place_order=true">Place Order</a></p>
                            </div>
                        </div>        
                    </div>
                </div>
        </main>
        <?php
            } else if($flag == 4) { // show order placed
        ?>
        <script>
            myVar= setTimeout(function() {
                document.getElementById('redirect').classList.remove('hide');
            }, 1000);
            document.getElementById("orderPlaced").onload = setTimeout(function() {
                window.location.replace("useraccount.php?orders=true");
            }, 4000);
        </script>
         <main id="top">
                <div class="main-body">
                    <div class="data">
                        <div class="top-menu">
                            <div class="ul-div">
                                <ul>
                                    <li><p style="color: #6ECB63;" >1. Delivery Address <i class="fa fa-check"></i></p></li>
                                    <li><p  style="color: #6ECB63;" >2. Payment method  <i class="fa fa-check"></i></a></li>
                                    <li><p style="color: #6ECB63;" >3. Place Order <i class="fa fa-check"></i></a></li>
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
                                stroke: #7ac142;
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
                                box-shadow: inset 0px 0px 0px #7ac142;
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
                            <h1 id="orderPlaced">Order placed.</h1>
                            <div class="redirect hide" id="redirect">
                                <p>Redirecting</p>
                                <div class="loader-container">
                                    <div id="loader"></div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
        </main>
        <?php
            } 
        ?>     
    
    </body>
</html>