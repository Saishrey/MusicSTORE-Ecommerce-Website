<?php 
    require "../private/autoload.php";

    $contact = "";
    $address = "";
    $user_name = "";

    $error_stmnt = "";
    $error_num = 0;
    $error = False;

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        //something was posted
        // to check if username matches pattern
        $user_name = trim($_POST['user_name']);
        if(strlen($user_name) != 0 && !preg_match("/^[a-zA-Z0-9 _]+$/", $user_name) && !$error) {
            $error_stmnt .= "<p style='color:#F78812; font-size:14px'>";
            $error_stmnt .= "<i class='fa fa-exclamation-circle'></i> ";
            $error_stmnt .= "Usernames can only use letters, numbers, <br />spaces and underscore.";
            $error_stmnt .= "</p>";
            $error = True;
        }
        $user_name = esc($user_name);

        // to check if contact number is 10 digits
        $contact = $_POST['contact'];
        if(strlen($contact) != 0 && strlen($contact) < 10 && !$error) {
            $error_stmnt .= "<p style='color:#F78812; font-size:14px'>";
            $error_stmnt .= "<i class='fa fa-exclamation-circle'></i> ";
            $error_stmnt .= "Contact number should be 10 digit only.";
            $error_stmnt .= "</p>";
            $error = True;
            $error_num = 1;
        }
        
        // to check if address is <= 250
        $address = $_POST['address'];
        if(strlen($address) > 250 && !$error) {
            $error_stmnt .= "<p style='color:#F78812; font-size:14px'>";
            $error_stmnt .= "<i class='fa fa-exclamation-circle'></i> ";
            $error_stmnt .= "Address length is 250 characters.";
            $error_stmnt .= "</p>";
            $error = True;
            $error_num = 2;
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

            //save to database
            $arr['user_name'] = $user_name;
            $arr['email'] = $_SESSION['email'];
            $arr['contact'] = $contact;
            $arr['address'] = $address;
            $query = "update customer set user_name=:user_name, contact=:contact, address=:address where email=:email";
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
                background: rgba(24,24,24, 0.8);
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
                margin-top: 100px;
                margin-bottom: 80px;
            }
            .form h2 {
                font-size: 3rem;
                margin-bottom: 40px;
                /* color: black; */
                color: white;
            }
            .form-control {
                padding: 14px 16px;
                font-size: 20px;
                width: 80%;
                margin: 15px;
                border: none;
                outline: none;
                border-radius: 6px;
                /* background-color: #F1F3F4; */
                background-color: #B8C1C6;
            }
            .form-control:focus {
                background-color: white;
                box-shadow: 0 5px 10px rgba(21,34,58,.13);
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

        </style>
            <header class="myheader" id="header">
                <input type="checkbox" id="check">
                <label for="check" class="checkbtn">
                    <i class="fa fa-bars"></i>
                </label>
                <a class="logo" href="index.php">Music<span style="color:#1b9bff;">STORE</span>&trade;</a>
                <ul class="nav-list">
                    <li><a class="active" href="index.php">Home</a></li>
                    <li><a class="active" href="#">Categories</a></li>
                    <li><a class="active" href="#">Orders</a></li>
                    <li><a class="active" href="#">Cart</a></li>
                    <li><a class="active" href="useraccount.php"><?=$_SESSION['user_name']?></a></li>
                </ul>                
        </header>
        <main id="top">
            <form method="post" class="form">
                <h2>Update Info</h2>
                <p style='color:yellow; font-size:12px'>Fill only those fields which you want to update.</p>
                <input type="text" class="form-control" name="user_name" placeholder="Username" value="<?=$_SESSION['user_name']?>" maxlength="20">
                <?php
                        if(isset($error_stmnt) && $error_num == 0 && $error_stmnt != "") {
                            echo $error_stmnt;
                        }
                ?>
                <input type="text" name="contact" class="form-control" placeholder="Contact" value="<?=$_SESSION['contact']?>" maxlength="10">
                <?php
                        if(isset($error_stmnt) && $error_num == 1 && $error_stmnt != "") {
                            echo $error_stmnt;
                        }
                ?>
                <input type="text" name="address" class="form-control" placeholder="Address" value="<?=$_SESSION['address']?>" maxlength="250">
                <?php
                        if(isset($error_stmnt) && $error_num == 2 && $error_stmnt != "") {
                            echo $error_stmnt;
                        }
                ?>
                <input type="submit" class="submit-btn" value="Update">
            </form>
        </main>
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="footer-col">
                        <p>Account</p>
                        <ul>
                            <li><a href="signup.php">Customer</a></li>
                            <li><a href="#">Seller</a></li>
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