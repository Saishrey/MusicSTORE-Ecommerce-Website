<?php

    require "../private/autoload.php";
    
    $email = "";
    $error_stmnt = "";
    $error = False;

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        //something was posted
        $email = $_SESSION['email'];

        $password = $_POST['password'];
        if((strlen($password) < 8 || strlen($password) > 16) && !$error) {
            $error_stmnt .= "<p style='color:#F78812; font-size:14px'>";
            $error_stmnt .= "<i class='fa fa-exclamation-circle'></i> ";
            $error_stmnt .= "Password must be 8-16 characters.";
            $error_stmnt .= "</p>";
            $error = True;
        }
        $password = esc($password);

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
                        deleteProfilePic();
                        $query = "delete from customer where email = :email";
                        $stmnt = $con->prepare($query);
                        $check = $stmnt->execute($arr);

                        session_destroy();
                        
                        echo "<script>
                            alert('Account Deleted successfully.');
                            window.location.replace('index.php');
                            </script>";
                    }
                }
            }
            $error_stmnt .= "<p style='color:#F78812; font-size:14px'>";
            $error_stmnt .= "<i class='fa fa-exclamation-circle'></i> ";
            $error_stmnt .= "Incorrect Password.";
            $error_stmnt .= "</p>";
            $error = True;
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
                    // $data = $data[0];
                    $otp = rand(100000,999999);
                    $_SESSION['otp'] = $otp;
                    $_SESSION['email'] = $email;
                    $_SESSION['new_password'] = True;

                    $string = "Password updation";
                    $mail_status = sendOTP($email, $otp, $string);
 
                    if(!$mail_status) {
                        echo "<script>
                            alert('Register Failed, Invalid Email');
                            </script>";
                    }
                    else {
                        header("Location: verification.php");
                        die;
                    }
                }
            }
            $error_stmnt .= "<p style='color:#F78812; font-size:14px'>";
            $error_stmnt .= "<i class='fa fa-exclamation-circle'></i> ";
            $error_stmnt .= "Invalid Email.";
            $error_stmnt .= "</p>";
            $error = True;
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
                background: url(images/large.jpg) no-repeat;
                background-size: center;
                font-family: 'Montserrat', sans-serif;
            }
            #box {
                display: flex;
                width: 500px;
                border: none;
                height: 450px;
                margin: auto;
                margin-top: 250px;
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
            .form label {
                color: white;
                font-size: 20px;
                margin: 15px;
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
        </style>
        <div id="box">
            <form method="post" class="form">
                <h2>Music<span style="color:#1b9bff;">STORE</span>&trade;</h2>
                <label for="password">Enter your password</label>
                <input type="password" name="password" class="form-control" placeholder="Password" required="required">
                <?php
                        if(isset($error_stmnt) && $error && $error_stmnt != "") {
                            echo $error_stmnt;
                        }
                ?>
                <input type="submit" class="submit-btn" value="Confirm">
            </form>
        </div>
    </body>
</html>