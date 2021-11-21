<?php

    require "../private/autoload.php";

    $user_name = "";
    $email = "";

    $error_stmnt = "";
    $error_num = 0;
    $error = False;

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        //something was posted
        // to check if username matches pattern
        $user_name = trim($_POST['user_name']);
        if(!preg_match("/^[a-zA-Z0-9 _]+$/", $user_name) && !$error) {
            $error_stmnt .= "<p style='color:#F78812; font-size:14px'>";
            $error_stmnt .= "<i class='fa fa-exclamation-circle'></i> ";
            $error_stmnt .= "Usernames can only use letters, numbers, <br />spaces and underscore.";
            $error_stmnt .= "</p>";
            $error = True;
            $error_num = 1;
        }
        $user_name = esc($user_name);
        
        // to check if email matches pattern
        $email = $_POST['email'];
        if(!preg_match("/^[\w\-]+@[\w\-]+.[\w\-]+$/", $email)  && !$error) {
            $error_stmnt .= "<p style='color:#F78812; font-size:14px'>";
            $error_stmnt .= "<i class='fa fa-exclamation-circle'></i> ";
            $error_stmnt .= "Please enter a valid email address.";
            $error_stmnt .= "</p>";
            $error = True;
            $error_num = 2;
        }

        // to check if email already exists
        if(!$error) {
            $email_arr['email'] = $email;

            $query = "select * from customer where email = :email limit 1";
            $stmnt = $con->prepare($query);
            $check = $stmnt->execute($email_arr);

            if($check) {
                $data = $stmnt->fetchAll(PDO::FETCH_OBJ);  //FETCH_ASSOC for array

                if(is_array($data) && count($data) > 0) {
                    $error_stmnt .= "<p style='color:#F78812; font-size:14px'>";
                    $error_stmnt .= "<i class='fa fa-exclamation-circle'></i> ";
                    $error_stmnt .= "An account with Email <span style='color:greenyellow; font-weight:bold'>$email</span> <br />already exists.";
                    $error_stmnt .= "</p>";
                    $error = True;
                    $error_num = 2;
                }
            }
        }

        // to check if password is within the specified limit
        $password = $_POST['password'];
        if((strlen($password) < 8 || strlen($password) > 16) && !$error) {
            $error_stmnt .= "<p style='color:#F78812; font-size:14px'>";
            $error_stmnt .= "<i class='fa fa-exclamation-circle'></i> ";
            $error_stmnt .= "Password must be 8-16 characters.";
            $error_stmnt .= "</p>";
            $error = True;
            $error_num = 3;
        }
        $password = esc($password);

        // check if password matches
        $confirm_password = $_POST['confirm_password'];
        if((strcmp($password, $confirm_password)) && !$error) {
            $error_stmnt .= "<p style='color:#F78812; font-size:14px'>";
            $error_stmnt .= "<i class='fa fa-exclamation-circle'></i> ";
            $error_stmnt .= "Passwords do not match.";
            $error_stmnt .= "</p>";
            $error = True;
            $error_num = 4;
        }

        if(!$error) {
            // genrate OTP
            $otp = rand(100000,999999);

            // send OTP
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $_SESSION['otp'] = $otp;
            $_SESSION['user_id'] = get_random_string(20);
            $_SESSION['user_name'] = $user_name;
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $password_hash;
            $_SESSION['new_password'] = False;
            $_SESSION['deactivate_account'] = False;


            $string = "MUSICSTORE Registration";

            $mail_status = sendOTP($email, $otp, $string);
 
            if(!$mail_status) {
                echo "<script>
                      alert('Register Failed, Invalid Email');
                      </script>";
            }
            else {
                //save to database

                // $arr['user_id'] = get_random_string(20);
                // $arr['user_name'] = $user_name;
                // $arr['email'] = $email;
                // $arr['password'] = $password_hash;
                // $query = "insert into customer (user_id, user_name, email, password) values (:user_id,:user_name,:email,:password)";
                // $stmnt = $con->prepare($query);
                // $stmnt->execute($arr);

                // echo "<script>
                //       alert('Registered Successfully, OTP sent to $email for email verification.');
                //       window.location.replace('verification.php');
                //       </script>";\
                header("Location: verification.php");
                die;
            }
            //     // $query = "insert into customer (user_id, user_name, email, password) values ('$user_id','$user_name','$email','$password')";
            //     // mysqli_query($con, $query);

            //     // $query = "insert into customer (user_id, user_name, email, password) values (?, ?, ?, ?)";
            //     // $stmnt = $con->prepare($query);
            //     // $stmnt->bind_param("ssss", $user_id, $user_name, $email, $password);
            //     // $stmnt->execute();

            // }
            
            
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
        <title>Sign up | MUSICSTORE</title>
    </head>
    <body>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Roboto:wght@100&family=Zen+Kaku+Gothic+Antique&display=swap');
            * {
                margin:0;
                padding: 0;
                box-sizing: border-box;
            }
            body {
                background-color: #ECEFF1;
                /* background-color: #181818; */
                background: url(images/large.jpg) no-repeat;
                background-size: center;
                font-family: 'Montserrat', sans-serif;
            }
            #box {
                display: flex;
                width: 500px;
                border: none;
                height: 750px;
                margin: auto;
                margin-top: 100px;
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
                /* background: rgba(255,255,255, 0.8); */

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
                background: #3DB026;
                text-align: center;
                text-decoration: none;
                font-family: 'Montserrat', sans-serif;
                color: white;
                font-size: 20px;
                text-transform: uppercase;
                border: none;
                outline: none;
                border-radius: 6px;
            }
            .submit-btn:hover {
                background: #369b22;
                transition: 0.3s;
                cursor: pointer;
            }
            hr {
                width: 80%;
                margin: 30px;
            }
            .form .sign-in {
                text-decoration: none;
                /* color: #4A616B; */
                color: #B8C1C6;
                font-size: 14px;
            }
            .form .sign-in:hover {
                /* color: #1A73E8; */
                color: #1b9bff;
            }
        </style>
        <div id="box">
            <form method="post" class="form">
                <h2>Music<span style="color:#1b9bff;">STORE</span>&trade;</h2>
                <input type="text" class="form-control" name="user_name" placeholder="Username" value="<?=$user_name?>" required="required">
                <?php
                    if(isset($error_stmnt) && $error_num == 1 && $error_stmnt != "") {
                        echo $error_stmnt;
                    }
                ?>
                <input type="email" class="form-control" name="email" placeholder="Email" value="<?=$email?>" maxlength="20" minlength="2" required="required">
                <?php
                    if(isset($error_stmnt) && $error_num == 2 && $error_stmnt != "") {
                        echo $error_stmnt;
                    }
                ?>
                <input type="password" name="password" class="form-control" placeholder="Password" required="required">
                <?php
                        if(isset($error_stmnt) && $error_num == 3 && $error_stmnt != "") {
                            echo $error_stmnt;
                        }
                ?>
                <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required="required">
                <?php
                        if(isset($error_stmnt) && $error_num == 4 && $error_stmnt != "") {
                            echo $error_stmnt;
                        }
                ?>
                <input type="submit" class="submit-btn" value="Sign up">
                <hr>
                <a href="login.php" class="sign-in">Sign in</a>
            </form>
        </div>
    </body>
</html>