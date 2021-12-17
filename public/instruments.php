<?php 

    require "../private/autoload.php";

    if(isset($_GET['instrument_id'])) {
        //remove instrument from database
        $array['i_id'] = $_GET['instrument_id'];

        $inst_img = $_GET['instrument_img'];

        deleteProfilePic($inst_img);

        $query_del = "delete from instrument where inst_id=:i_id;";
        $stmnt_del = $con->prepare($query_del);
        $check_del = $stmnt_del->execute($array);

        header("Location: instruments.php");
        die;
    } else {
        //fetch instruments from database
        $arr['s_id'] = $_SESSION['seller_id'];

        $query = "select * from instrument where s_id = :s_id";
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
            .data {
                width: 90%;
                background: white;
                min-width: 625px;
                margin: auto;
                z-index: 2;
                border-radius: 6px;
                box-shadow: 0 7px 20px rgba(50, 50, 93, .2);
                padding-bottom: 80px;
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

            .inst-up-rem {
                display: flex;
                flex-direction: row;
                width: 60%;
                margin: auto;
                padding: 40px;
            }
            .inst-up-rem .upload {
                text-decoration: none;
                padding: 10px 40px;
                background: #3DB026;
                color: white;
                margin: 30px;
                width: 100%;
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


            /* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */
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
                    <div class="main-body">
                        <div class="data">
                            <div class="top-menu">
                                <div class="ul-div">
                                    <ul>
                                        <li><a href="selleraccount.php">Account</a></li>
                                        <li><a class="active" href="instruments.php">Instruments</a></li>
                                        <li><a href="selleraccount.php?sales=true">Sales</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="inst-up-rem">
                                <a href="uploadInstrument.php" class="upload">Upload Instrument</a>
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
                                            <a class="remove-inst" href="instruments.php?instrument_id=<?=$inst_data[$i]->inst_id?>&instrument_img=<?=$inst_data[$i]->inst_img?>">Remove Instrument</a>
                                        </div>
                                    </div>
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