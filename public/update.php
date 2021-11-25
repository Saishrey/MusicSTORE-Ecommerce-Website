<?php 
    require "../private/autoload.php";

    $contact = "";
    $address = "";
    $user_name = "";
    $pin_code = "";

    $error_stmnt = "";
    $error_num = 0;
    $error = False;

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        //something was posted
        // to check if username matches pattern
        $user_name = trim($_POST['user_name']);
        if(strlen($user_name) != 0 && !preg_match("/^[a-zA-Z0-9 _]+$/", $user_name) && !$error) {
            $error_stmnt .= "<p style='color:#F78812; font-size:14px'>";
            $error_stmnt .= "<i class='fa fa-exclamation-circle'></i> ";
            $error_stmnt .= "Usernames can only use letters, numbers, <br />spaces and underscore.";
            $error_stmnt .= "</p>";
            $error = True;
            $error_num = 1;
        }
        $user_name = esc($user_name);

        // to check if contact number is 10 digits
        $contact = $_POST['contact'];
        if(strlen($contact) != 0 && strlen($contact) < 10 && !$error) {
            $error_stmnt .= "<p style='color:#F78812; font-size:14px'>";
            $error_stmnt .= "<i class='fa fa-exclamation-circle'></i> ";
            $error_stmnt .= "Contact number should be 10 digits.";
            $error_stmnt .= "</p>";
            $error = True;
            $error_num = 2;
        }
        
        // to check if address is <= 250
        $address = $_POST['address'];
        if(strlen($address) > 250 && !$error) {
            $error_stmnt .= "<p style='color:#F78812; font-size:14px'>";
            $error_stmnt .= "<i class='fa fa-exclamation-circle'></i> ";
            $error_stmnt .= "Address length is 250 characters.";
            $error_stmnt .= "</p>";
            $error = True;
            $error_num = 3;
        }

        // to check if pincode is <= 6
        $pin_code = $_POST['pin_code'];
        if(strlen($pin_code) != 0 && strlen($pin_code) < 6 && !$error) {
            $error_stmnt .= "<p style='color:#F78812; font-size:14px'>";
            $error_stmnt .= "<i class='fa fa-exclamation-circle'></i> ";
            $error_stmnt .= "PIN Code must be 6 digits.";
            $error_stmnt .= "</p>";
            $error = True;
            $error_num = 4;
        }

        if(!$error) {

            if(strlen($user_name) == 0) {
                $user_name = $_SESSION['user_name'];
            }
            else {
                $_SESSION['user_name'] = $user_name;
            }

            if(strlen($contact) == 0) {
                $contact= $_SESSION['contact'];
            }
            else {
                $_SESSION['contact'] = $contact;
            }

            if(strlen($address) == 0) {
                $address = $_SESSION['address'];
            }
            else {
                $_SESSION['address'] = $address;
            }

            if(strlen($pin_code) == 0) {
                $pin_code = $_SESSION['pin_code'];
            }
            else {
                $_SESSION['pin_code'] = $pin_code;
            }

            //save to database
            $arr['user_name'] = $user_name;
            $arr['email'] = $_SESSION['email'];
            $arr['contact'] = $contact;
            $arr['address'] = $address;
            $arr['pin_code'] = $pin_code;
            $query = "update customer set user_name=:user_name, contact=:contact, address=:address, pin_code=:pin_code where email=:email";
            $stmnt = $con->prepare($query);
            $stmnt->execute($arr);

            echo "<script>
                  alert('Data updated successfully.');
                  window.location.replace('useraccount.php');
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
        <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" /> -->
        <!-- script  -->
        <!-- <script src="https://code.jquery.com/jquery-3.5.1.js" type="text/javascript"></script> -->
        <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" type="text/javascript"></script> -->
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
                background: url(images/large.jpg) no-repeat;
                background-size: auto;
                font-family: 'Montserrat', sans-serif;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }
            header {
                /* background: #0082e6; */
                /* background: #181818; */
                /* background: rgba(24,24,24, 0.9); */
                background: rgb(24,24,24);
                height: 80px;
                width: 100%;
                border-bottom: 1px solid #1b9bff;
                position: fixed;
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

            /* Update */
            main {
                margin-top: 80px;
                background: rgba(24,24,24, 0.9);
                padding-bottom: 100px;
            }
            .form {
                /* display: flex;
                flex-direction: column; */
                width: 40%;
                min-width: 400px;
                text-align: center;
                /* align-items: center; */
                margin: auto;
                margin-top: 20px;
                margin-bottom: 10px;
            }
            main h2 {
                font-size: 3rem;
                margin: 80px 0 20px 0;
                /* color: black; */
                color: white;
                text-align: center;
            }
            .input-data {
                padding: 14px 16px;
                font-size: 20px;
                width: 80%;
                margin: 15px;
                border: none;
                outline: none;
                border-radius: 6px;
                /* background-color: #F1F3F4; */
                background-color: #B8C1C6;
                font-family: 'Montserrat', sans-serif;
            }
            .input-data:focus {
                background-color: white;
                box-shadow: 0 5px 10px rgba(21,34,58,.13);
            }
            .delete-form {
                /* display: flex;
                flex-direction: column; */
                width: 40%;
                min-width: 400px;
                text-align: center;
                /* align-items: center; */
                margin: auto;
                margin-bottom: 50px;
            }
            .delete-form .hidden {
                display: none;
            }
            .submit-btn {
                padding: 12px 30px;
                width: 80%;
                margin-top: 15px;
                /* background-color: #32AEF2; */
                /* background: #181818; */
                background: none;
                color: white;
                font-size: 20px;
                font-family: 'Montserrat', sans-serif;
                text-transform: uppercase;
                border: 1px solid white;
                outline: none;
                border-radius: 6px;
            }
            .submit-btn:hover {
                cursor: pointer;
                /* background-color: #195aaf; */
                background: #1b9bff;
                border: 1px solid #1b9bff;
                transition: 0.3s;
            }
            .delete-btn {
                padding: 12px 30px;
                width: 80%;
                /* margin-top: 15px; */
                /* background-color: #32AEF2; */
                /* background: #181818; */
                background: none;
                color: white;
                font-size: 20px;
                font-family: 'Montserrat', sans-serif;
                text-transform: uppercase;
                border: 1px solid white;
                outline: none;
                border-radius: 6px;
            }
            .delete-btn:hover {
                cursor: pointer;
                /* background-color: #195aaf; */
                background: red;
                border: 1px solid red;
                transition: 0.3s;
            }
            .form .forgot-pass {
                text-decoration: none;
                /* color: #4A616B; */
                color: #B8C1C6;
                margin-top: 20px;
                font-size: 14px;
            }
            .form .forgot-pass:hover {
                /* color: #1A73E8; */
                color: #1b9bff;
            }
            hr {
                width: 80%;
                margin: 30px;
            }
            .sign-up {
                padding: 12px 30px;
                width: 80%;
                margin-top: 15px;
                /* background-color: #32AEF2; */
                /* background: #181818; */
                background: #3DB026;
                text-align: center;
                text-decoration: none;
                color: white;
                font-size: 18px;
                text-transform: uppercase;
                /* border: 1px solid white; */
                outline: none;
                border-radius: 6px;
            }
            .sign-up:hover {
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
                color: #ffffff;
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
            .alert{
                position: absolute;
                z-index: 99999;
                right: 1%;1
                top:10%;
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
                    <li><a class="active" href="useraccount.php"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a></li>
                </ul>                
        </header>
        <main id="top">
            <h2>Update Info</h2>
            <form action="../private/upload_image.php" method="post" enctype="multipart/form-data" class="form">
                <label for="profile_pic"><span style="color: white;">Profile Picture</span></label>
                <input type="file" id="profile_pic" class="input-data" name="imageFile">
                <input type="submit" name="upload-image" class="submit-btn" value="Upload">
            </form>
            <?php
                if($_SESSION['img_name'] != null) {
            ?>
            <form action="../private/delete_image.php" method="post" accept-charset="utf-8" class="delete-form">
                <input class="hidden" type="text" name="delete">
                <input type="submit" class="delete-btn" value="Delete Profile Picture">
            </form>
            <?php
                }
            ?>
            <form method="post" class="form">
                <p style='color:yellow; font-size:12px'>Fill only those fields which you want to update.</p>
                <input type="text" class="input-data" name="user_name" placeholder="Username" value="<?=$_SESSION['user_name']?>" maxlength="20" title="Username">
                <?php
                        if(isset($error_stmnt) && $error_num == 1 && $error_stmnt != "") {
                            echo $error_stmnt;
                        }
                ?>
                <input type="text" name="contact" class="input-data" placeholder="Contact" value="<?=$_SESSION['contact']?>" maxlength="10" title="Contact">
                <?php
                        if(isset($error_stmnt) && $error_num == 2 && $error_stmnt != "") {
                            echo $error_stmnt;
                        }
                ?>
                <input type="text" name="address" class="input-data" placeholder="Address" value="<?=$_SESSION['address']?>" maxlength="250" title="Address">
                <?php
                        if(isset($error_stmnt) && $error_num == 3 && $error_stmnt != "") {
                            echo $error_stmnt;
                        }
                ?>
                <input type="text" name="pin_code" class="input-data" placeholder="PIN Code" value="<?=$_SESSION['pin_code']?>" maxlength="6" title="Pin code">
                <?php
                        if(isset($error_stmnt) && $error_num == 4 && $error_stmnt != "") {
                            echo $error_stmnt;
                        }
                ?>
                <input type="submit" class="submit-btn" value="Update" name="update">
            </form>
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
                            <li><a href="sellerGreeting.php">Seller</a></li>
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