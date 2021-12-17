<?php

    require "../private/autoload.php";
    
    $email = "";
    $agent_id = '';
    $agent_pin = '';
    $error_stmnt = "";
    $error_num = 0;
    $error = False;

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
        //something was posted
        $email = $_POST['email'];
        if(!preg_match("/^[\w\-]+@[\w\-]+.[\w\-]+$/", $email) && !$error) {
            $error_stmnt .= "<p style='color:#F78812; font-size:14px'>";
            $error_stmnt .= "<i class='fa fa-exclamation-circle'></i> ";
            $error_stmnt .= "Please enter a valid email address.";
            $error_stmnt .= "</p>";
            $error = True;
            $error_num = 1;
        }

        $password = $_POST['password'];
        $password = esc($password);
        if((strlen($password) < 8 || strlen($password) > 16) && !$error) {
            $error_stmnt .= "<p style='color:#F78812; font-size:14px'>";
            $error_stmnt .= "<i class='fa fa-exclamation-circle'></i> ";
            $error_stmnt .= "Password must be 8-16 characters.";
            $error_stmnt .= "</p>";
            $error = True;
            $error_num = 2;
        }

        if(!$error) {
            //read from database
            $arr['email'] = $email;

            $query = "select * from customer where email = :email limit 1";
            $stmnt = $con->prepare($query);
            $check = $stmnt->execute($arr);

            if($check) {
                $data = $stmnt->fetchAll(PDO::FETCH_OBJ);  //FETCH_ASSOC for array

                if(is_array($data) && count($data) > 0) {
                    $data = $data[0];
                    $password_hash = $data->password;
                    if(password_verify($password, $password_hash)) {
                        $_SESSION['user_id'] = $data->user_id;
                        $_SESSION['user_name'] = $data->user_name;
                        $_SESSION['email'] = $data->email;
                        $_SESSION['contact'] = $data->contact;
                        $_SESSION['img_name'] = $data->img_name;
                        $_SESSION['is_seller'] = $data->is_seller;

                        if($_SESSION['email'] == "your_admin_email") {  // place your admin email
                            header("Location: adminPage.php");
                            die;
                        }
                        if($_SESSION['is_seller'] == 1) {
                            $query = "select * from seller where seller_email = :email limit 1";
                            $stmnt2 = $con->prepare($query);
                            $check_seller = $stmnt2->execute($arr);

                            if($check_seller) {
                                $seller_data = $stmnt2->fetchAll(PDO::FETCH_OBJ);  //FETCH_ASSOC for array
                                if(is_array($seller_data) && count($seller_data) > 0) {
                                    $seller_data = $seller_data[0];
                                    
                                    $_SESSION['seller_id'] = $seller_data->seller_id;
                                    $_SESSION['company_name'] = $seller_data->company_name;
                                    $_SESSION['seller_contact'] = $seller_data->seller_contact;
                                    $_SESSION['seller_address'] = $seller_data->seller_address;
                                    $_SESSION['seller_pin_code'] = $seller_data->seller_pin_code;
                                    $_SESSION['seller_dp'] = $seller_data->seller_dp;

                                    header("Location: index.php");
                                    die;
                                } 
                            }             
                        }
                        else {
                            header("Location: index.php");
                            die;
                        }
                        
                    }
                }
            }
            $error_stmnt .= "<p style='color:#F78812; font-size:14px'>";
            $error_stmnt .= "<i class='fa fa-exclamation-circle'></i> ";
            $error_stmnt .= "Invalid Email or Password.";
            $error_stmnt .= "</p>";
        }
    }
    else if(isset($_POST['id'])) {
        //agent log in
        $agent_id = $_POST['id'];
        if(substr($agent_id,0,6) != "AGENT_" && !preg_match("/^[0-9]*$/", substr($agent_id, 6)) && !$error) {
            $error_stmnt .= "<p style='color:#F78812; font-size:14px'>";
            $error_stmnt .= "<i class='fa fa-exclamation-circle'></i> ";
            $error_stmnt .= "Please enter a valid ID.";
            $error_stmnt .= "</p>";
            $error = True;
            $error_num = 1;
        }

        $agent_pin= $_POST['pin'];
        if(strlen($agent_pin) !=4 && !preg_match(substr("/^[0-9]*$/", $agent_pin)) && !$error) {
            $error_stmnt .= "<p style='color:#F78812; font-size:14px'>";
            $error_stmnt .= "<i class='fa fa-exclamation-circle'></i> ";
            $error_stmnt .= "PIN must be 4 Digits.";
            $error_stmnt .= "</p>";
            $error = True;
            $error_num = 2;
        }

        if(!$error) {
            //read from database
            $arr['agent_id'] = $agent_id;

            $query = "select * from agent where agent_id = :agent_id limit 1";
            $stmnt = $con->prepare($query);
            $check = $stmnt->execute($arr);

            if($check) {
                $data = $stmnt->fetchAll(PDO::FETCH_OBJ);  //FETCH_ASSOC for array

                if(is_array($data) && count($data) > 0) {
                    $data = $data[0];
                    if($agent_pin == $data->agent_password) {
                        $_SESSION['agent_id'] = $data->agent_id;
                        $_SESSION['agent_name'] = $data->agent_name;
                        $_SESSION['agent_contact'] = $data->agent_contact;
                        $_SESSION['agent_city'] = $data->agent_city;
                        $_SESSION['agent_state'] = $data->agent_state;
                        $_SESSION['agent_country'] = $data->agent_country;
                        $_SESSION['agent_pin_code'] = $data->agent_pin_code;

                        header('Location: agentPage.php?agent_query=orders');
                        die;
                    }
                }
                $error_stmnt .= "<p style='color:#F78812; font-size:14px'>";
                $error_stmnt .= "<i class='fa fa-exclamation-circle'></i> ";
                $error_stmnt .= "Invalid ID or PIN.";
                $error_stmnt .= "</p>";
            }
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
        <title>Sign in | MUSICSTORE</title>
    </head>
    <body>
        <!-- <style>
            #box {
                height: 500px;
            }
            .right-side img {
                height: 500px;
            }
        </style> -->
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Roboto:wght@100&family=Zen+Kaku+Gothic+Antique&display=swap');
            * {
                margin:0;
                padding: 0;
                box-sizing: border-box;
            }
            body {
                /* background-color: #ECEFF1; */
                /* background: rgba(24,24,24, 0.8); */
                /* background-color: #181818; */
                /* background: url(images/large.jpg) no-repeat; */
                background: url(images/del_agent.jpg) no-repeat;
                background-size: center;
                font-family: 'Montserrat', sans-serif;
            }
            #box {
                display: flex;
                width: 500px;
                border: none;
                height: 650px;
                margin: auto;
                margin-top: 150px;
                /* box-shadow: 5px 5px 10px gray; */
                border-radius: 6px;
            }
            .form {
                display: flex;
                flex-direction: column;
                border-radius: 6px;
                width: 100%;
                align-items: center;
                /* background-color: #A2DBFA; */
                /* background-color: #373737; */
                /* background: #181818; */
                background: rgba(24,24,24, 0.8);
            }
            .form h2 {
                font-size: 3rem;
                margin: 80px 0 40px 0;
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
                background: rgba(24,24,24,0.2);
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
                color: white;
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
        </style>
        <?php
            if(isset($_GET['agentLogin']) && $_GET['agentLogin'] == 'true') {
        ?>
        <style>
            body {
                background: url(images/del_agent.jpg) no-repeat;
                background-size: center;
                font-family: 'Montserrat', sans-serif;
            }
            #box {
                height: 500px;
                margin-top: 200px;
            }
            .form {
                background: whitesmoke;
            }
            .form h2 {
                color: black;
            }
            .submit-btn {
                background: none;
                border: 2px solid #1b9bff;
                color: #1b9bff;
            }
            .submit-btn:hover {
                border: 2px solid #1b9bff;
            }
        </style>
        <div id="box">
            <form method="post" class="form">
                <h2>Music<span style="color:#1b9bff;">STORE</span>&trade;<sub style="font-size: 18px;">Agent</sub></h2>
                <?php
                        if(isset($error_stmnt) && $error_num == 0 && $error_stmnt != "") {
                            echo $error_stmnt;
                        }
                ?>
                <input type="text" name="id" class="form-control" placeholder="Agent ID"  value="<?=$email?>" required="required">
                <?php
                        if(isset($error_stmnt) && $error_num == 1 && $error_stmnt != "") {
                            echo $error_stmnt;
                        }
                ?>
                <input type="password" name="pin" class="form-control" placeholder="PIN" required="required">
                <?php
                        if(isset($error_stmnt) && $error_num == 2 && $error_stmnt != "") {
                            echo $error_stmnt;
                        }
                ?>
                <input type="submit" class="submit-btn" value="Sign in">
            </form>
        </div>
        <?php
            } else {
        ?>
        <div id="box">
            <form method="post" class="form">
                <h2>Music<span style="color:#1b9bff;">STORE</span>&trade;</h2>
                <?php
                        if(isset($error_stmnt) && $error_num == 0 && $error_stmnt != "") {
                            echo $error_stmnt;
                        }
                ?>
                <input type="email" name="email" class="form-control" placeholder="Email"  value="<?=$email?>" required="required">
                <?php
                        if(isset($error_stmnt) && $error_num == 1 && $error_stmnt != "") {
                            echo $error_stmnt;
                        }
                ?>
                <input type="password" name="password" class="form-control" placeholder="Password" required="required">
                <?php
                        if(isset($error_stmnt) && $error_num == 2 && $error_stmnt != "") {
                            echo $error_stmnt;
                        }
                ?>
                <input type="submit" class="submit-btn" value="Sign in">
                <a href="confirmEmail.php" class="forgot-pass">Forgot password?</a>
                <hr>
                <a href="signup.php" class="sign-up">Create new account</a>
            </form>
        </div>
        <?php
            }
        ?>
    </body>
</html>