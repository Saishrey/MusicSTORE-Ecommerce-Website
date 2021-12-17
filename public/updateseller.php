<?php 
    require "../private/autoload.php";

    $seller_contact = "";
    $seller_address = "";
    $company_name = "";
    $seller_pin_code = "";

    $error = False;

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        //something was posted
        // to check if username matches pattern
        $company_name = trim($_POST['company_name']);
        if(strlen($company_name) != 0 && !preg_match("/^[a-zA-Z .]+$/", $company_name) && !$error) {
            echo "<script>
                  alert('Please refill the form. Company name can only use letters, spaces and dots.');
                  window.location.replace('selleraccount.php');
                  </script>"; 
            $error = True;
        }
        $company_name = esc($company_name);

        // to check if contact number is 10 digits
        $seller_contact = $_POST['seller_contact'];
        if(strlen($seller_contact) != 0 && strlen($seller_contact) < 10 && !$error) {
            echo "<script>
                  alert('Please refill the form. Contact number should be 10 digits.');
                  window.location.replace('selleraccount.php');
                  </script>"; 
            $error = True;
        }
        
        // to check if address is <= 250
        $seller_address = $_POST['seller_address'];
        if(strlen($seller_address) > 250 && !$error) {
            echo "<script>
                  alert('Please refill the form. Address length is 250 characters.');
                  window.location.replace('selleraccount.php');
                  </script>"; 
            $error = True;
        }

        // to check if pincode is <= 6
        $seller_pin_code = $_POST['seller_pin_code'];
        if(strlen($seller_pin_code) != 0 && strlen($seller_pin_code) < 6 && !$error) {
            echo "<script>
                  alert('Please refill the form. Pincode must be 6 digits.');
                  window.location.replace('selleraccount.php');
                  </script>"; 
            $error = True;
        }

        if(!$error) {

            if(strlen($company_name) == 0) {
                $company_name = $_SESSION['company_name'];
            }
            else {
                $_SESSION['company_name'] = $company_name;
            }

            if(strlen($seller_contact) == 0) {
                $seller_contact= $_SESSION['seller_contact'];
            }
            else {
                $_SESSION['seller_contact'] = $seller_contact;
            }

            if(strlen($seller_address) == 0) {
                $seller_address = $_SESSION['seller_address'];
            }
            else {
                $_SESSION['seller_address'] = $seller_address;
            }

            if(strlen($seller_pin_code) == 0) {
                $seller_pin_code = $_SESSION['seller_pin_code'];
            }
            else {
                $_SESSION['seller_pin_code'] = $seller_pin_code;
            }

            //save to database
            
            $arr['company_name'] = $company_name;
            $arr['email'] = $_SESSION['email'];
            $arr['seller_contact'] = $seller_contact;
            $arr['seller_address'] = $seller_address;
            $arr['seller_pin_code'] = $seller_pin_code;
            $query = "update seller set company_name=:company_name, seller_contact=:seller_contact, seller_address=:seller_address, seller_pin_code=:seller_pin_code where seller_email=:email";
            $stmnt = $con->prepare($query);
            $stmnt->execute($arr);

            $arr2['s_id'] = $_SESSION['seller_id'];
            $arr2['company_name'] = $company_name;
            $query2 = "update instrument set brand_name=:company_name where s_id=:s_id";
            $stmnt2 = $con->prepare($query2);
            $stmnt2->execute($arr2);


            echo "<script>
                  alert('Data updated successfully.');
                  window.location.replace('selleraccount.php');
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
        <title> Update seller profile picture | MusicSTORE</title>
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
                /* background: url(images/large.jpg) no-repeat;
                background-size: auto; */
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
                background: whitesmoke;
                height: 70vh;
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
                color: black;
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
                color: black;
                font-size: 20px;
                font-family: 'Montserrat', sans-serif;
                text-transform: uppercase;
                border: 1px solid black;
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
            .delete-btn {
                padding: 12px 30px;
                width: 80%;
                /* margin-top: 15px; */
                /* background-color: #32AEF2; */
                /* background: #181818; */
                background: none;
                color: black;
                font-size: 20px;
                font-family: 'Montserrat', sans-serif;
                text-transform: uppercase;
                border: 1px solid black;
                outline: none;
                border-radius: 6px;
            }
            .delete-btn:hover {
                cursor: pointer;
                /* background-color: #195aaf; */
                color: white;
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
            .hidden-input {
                display: none;
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
                    <li><a class="active" href="selleraccount.php"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a></li>
                </ul>                
        </header>
        <main id="top">
            <h2>Change Profile Picture</h2>
            <form action="../private/upload_image.php" method="post" enctype="multipart/form-data" class="form">
                <input type="file" id="profile_pic" class="input-data" name="imageFile" required="required">
                <input type="submit" name="seller_upload_image" class="submit-btn" value="Upload">
            </form>
            <?php
                if($_SESSION['seller_dp'] != null) {
            ?>
            <form action="../private/delete_image.php" method="post" accept-charset="utf-8" class="delete-form">
                <input class="hidden" type="text" >
                <input type="submit" class="delete-btn" name="delete_seller_dp" value="Delete Profile Picture">
            </form>
            <?php
                }
            ?>
        </main>
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="footer-col">
                        <p>Account</p>
                        <ul>
                            <li><a href="useraccount.php">Customer</a></li>
                            <li><a href="selleraccount.php">Seller</a></li>
                

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