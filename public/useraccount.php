<?php 

    require "../private/autoload.php";

    $contact = "";
    $locality = "";
    $city = "";
    $state = "";
    $country = "";
    $user_name = "";
    $pin_code = "";

    $error = False;

    if(isset($_POST['user_name'])) {
        // to check if username matches pattern
        $user_name = trim($_POST['user_name']);
        if(strlen($user_name) != 0 && !preg_match("/^[a-zA-Z0-9 _]+$/", $user_name) && !$error) {
            echo "<script>
                  alert('Please refill the form. Usernames can only use letters, numbers, spaces and underscore.');
                  window.location.replace('useraccount.php');
                  </script>"; 
            $error = True;
        }
        $user_name = esc($user_name);

        if(!$error) {
            if(strlen($user_name) == 0) {
                $user_name = $_SESSION['user_name'];
            }
            else {
                $_SESSION['user_name'] = $user_name;
            }
        }

        //save to database
        $arr['user_name'] = $user_name;
        $arr['email'] = $_SESSION['email'];
        $query = "update customer set user_name=:user_name where email=:email";
        $stmnt = $con->prepare($query);
        $stmnt->execute($arr);

        $_SESSION['user_name'] = $user_name;
        echo "<script>
                alert('Username updated successfully.');
                window.location.replace('useraccount.php');
                </script>";        
    } 
    else if(isset($_POST['contact'])) {
       // to check if contact number is 10 digits
       $contact = $_POST['contact'];
       if(strlen($contact) != 0 && strlen($contact) < 10 && !$error) {
           echo "<script>
                 alert('Please refill the form. Contact number should be 10 digits.');
                 window.location.replace('useraccount.php');
                 </script>"; 
           $error = True;
       }

       if(!$error) {
            if(strlen($contact) == 0) {
                $contact= $_SESSION['contact'];
            }
            else {
                $_SESSION['contact'] = $contact;
            }
        }

        //save to database
        $arr['contact'] = $contact;
        $arr['email'] = $_SESSION['email'];
        $query = "update customer set contact=:contact where email=:email";
        $stmnt = $con->prepare($query);
        $stmnt->execute($arr);

        $_SESSION['contact'] = $contact;
        echo "<script>
                alert('Contact updated successfully.');
                window.location.replace('useraccount.php');
                </script>";        
    } 
    else if($_SERVER['REQUEST_METHOD'] == 'POST') {
        // address
        // to check if address is <= 250
        $locality= $_POST['locality'];
        if(strlen($locality) > 250 && !$error) {
            echo "<script>
                  alert('Please refill the form. Locality length is maximum 250 characters.');
                  window.location.replace('useraccount.php');
                  </script>"; 
            $error = True;
        }
        $locality = esc($locality);

        $city = $_POST['city'];
        if(strlen($city) > 250 && !$error) {
            echo "<script>
                  alert('Please refill the form. City length is maximum 100 characters.');
                  window.location.replace('useraccount.php');
                  </script>"; 
            $error = True;
        }
        $city = esc($city);

        // to check if pincode is <= 6
        $pin_code = $_POST['pin_code'];
        if(strlen($pin_code) != 0 && strlen($pin_code) < 6 && !$error) {
            echo "<script>
                  alert('Please refill the form. Pincode must be 6 digits.');
                  window.location.replace('useraccount.php');
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
              window.location.replace('useraccount.php');
              </script>";
    }
    else if(isset($_GET['remove_address'])) {
        //remove address from database
        $array['a_id'] = $_GET['remove_address'];

        $query_del = "delete from address where address_id=:a_id;";
        $stmnt_del = $con->prepare($query_del);
        $check_del = $stmnt_del->execute($array);

        header("Location: useraccount.php");
        die;
    }

    $arr['c_id'] = $_SESSION['user_id'];
    $query = "select * from address where c_id=:c_id";
    $stmnt = $con->prepare($query);
    $check = $stmnt->execute($arr);

    if($check) {
        $cust_data = $stmnt->fetchAll(PDO::FETCH_OBJ);  //FETCH_ASSOC for array
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
                /* background: rgb(24,24,24); */
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
                font-family: 'Montserrat', sans-serif;
                background: none;
                outline: none;
                width: 50%;
                margin: auto;
                margin-top: 20px;
                margin-bottom: 20px;
                border: none;
                font-size: 20px;
                text-align: center;
                color: purple;
            }
            .add-address:hover {
                cursor: pointer;
                color: #1b9bff;
                transition: 250ms linear;
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
                    <div class="main-body">
                        <div class="data">
                            <div class="top-menu">
                                <div class="ul-div">
                                    <ul>
                                        <li><a class="active" href="useraccount.php">My Account</a></li>
                                        <li><a href="#">Orders</a></li>
                                        <li><a href="cart.php">Cart</a></li>
                                    </ul>
                                </div>
                            </div>
                            <!-- <form method="post" action="updateseller.php">
                                <h2>Account details</h2>
                                <p style='color:orange; font-size:14px'>Fill only those fields which you want to update.</p>
                                <div class="item">
                                    <label for="email">Email:</label>
                                    <p class="fix-email" style="color:#grey;" id="email" title="Cannot edit email"><?=$_SESSION['email']?></p>
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
                                <a href="#" class="deactivate">Deactivate Account</a>
                            </form> -->
                            <div class="account-details">
                                <h2>Account details</h2>
                                <p style='color:orange; font-size:14px'>Fill only those fields which you want to update.</p>
                                <div class="item">
                                    <h3>Email:</h3>
                                    <p class="fix-email" style="color:#grey;" id="email" title="Cannot edit email"><?=$_SESSION['email']?></p>
                                </div>
                                <form action="useraccount.php" method="post">
                                <div class="item">
                                    <h3>Username:</h3>
                                    <input type="text" class="form-control" id="username" name="user_name" placeholder="Username" value="<?=$_SESSION['user_name']?>" maxlength="20" title="Username">
                                    <div class="update-submit">
                                        <input type="submit" class="submit-btn" value="Update" name="update">
                                    </div>    
                                </div>
                                </form>   
                                <form action="useraccount.php" method="post">
                                <div class="item">
                                    <h3>Contact:</h3>
                                    <input type="text" name="contact" id="contact" class="form-control" placeholder="Contact" value="<?=$_SESSION['contact']?>" maxlength="10" title="Contact">
                                    <div class="update-submit">
                                        <input type="submit" class="submit-btn" value="Update" name="update">
                                    </div>  
                                </div>
                                </form>
                                <?php
                                    if(isset($cust_data) && count($cust_data) > 0) {
                                        for($i = 0; $i < count($cust_data); $i++) {
                                ?>
                                <div class="address">
                                    <form>
                                        <h3>Address <?=$i+1?></h3>
                                        <div class="item">
                                            <h3>Locality:</h3>
                                            <p class="fix-email" style="color:#grey; text-align: left;" id="email" ><?=$cust_data[$i]->locality?></p>
                                        </div>
                                        <div class="item">
                                            <h3>City:</h3>
                                            <p class="fix-email" style="color:#grey; text-align: left;" id="email" ><?=$cust_data[$i]->city?></p>
                                        </div>
                                        <div class="item">
                                            <h3>State:</h3>
                                            <p class="fix-email" style="color:#grey; text-align: left;" id="email" ><?=$cust_data[$i]->state?></p>
                                        </div>
                                        <div class="item">
                                            <h3>Country:</h3>
                                            <p class="fix-email" style="color:#grey; text-align: left;" id="email" ><?=$cust_data[$i]->country?></p>
                                        </div>
                                        <div class="item">
                                            <h3>PIN Code:</h3>
                                            <p class="fix-email" style="color:#grey; text-align: left;" id="email" ><?=$cust_data[$i]->pin_code?></p>
                                        </div> 
                                        <div class="sbmt">
                                            <a class="add-address" href="useraccount.php?remove_address=<?=$cust_data[$i]->address_id?>">Remove address</a>
                                        </div>
                                    </form>
                                </div>
                                <?php
                                        }
                                    }
                                ?>
                                <?php
                                    if(isset($_GET['add_address']) && $_GET['add_address'] == 'true') {
                                ?>
                                <div class="address">
                                    <form action="useraccount.php" method="post">
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
                                            <input type="submit" class="submit-btn" value="Confirm" name="confirm">
                                        </div>
                                    </form>
                                </div>
                                <?php
                                    } else {
                                ?>
                                <a class="add-address" href="useraccount.php?add_address=true">Add address</a>
                                <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
        </main>
        <style>
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