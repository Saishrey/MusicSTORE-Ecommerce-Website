<?php

    require "../private/autoload.php";
    
    $email = "";
    $error_stmnt = "";
    $error_num = 0;
    $error = False;

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        //something was posted
        // to check if password is within the specified limit
        $password = $_POST['password'];
        if((strlen($password) < 8 || strlen($password) > 16) && !$error) {
            $error_stmnt .= "<p style='color:#F78812; font-size:14px'>";
            $error_stmnt .= "<i class='fa fa-exclamation-circle'></i> ";
            $error_stmnt .= "Password must be 8-16 characters.";
            $error_stmnt .= "</p>";
            $error = True;
            $error_num = 1;
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
            $error_num = 2;
        }

        if(!$error) {
            //read from database
            $arr['email'] = $_SESSION['email'];
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $arr['password'] = $password_hash;
            $query = "update customer set password = :password where email = :email";
            $stmnt = $con->prepare($query);
            $stmnt->execute($arr);

            unset($_SESSION['email']);
            unset($_SESSION['password']);

            echo "<script>
                  alert('New password set successfully.');
                  window.location.replace('index.php');
                  </script>";
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
                background: url(images/large.jpg) no-repeat;
                background-size: center;
                font-family: 'Montserrat', sans-serif;
            }
            #box {
                display: flex;
                width: 500px;
                border: none;
                height: 500px;
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
        <div id="box">
            <form method="post" class="form">
                <h2>Music<span style="color:#1b9bff;">STORE</span>&trade;</h2>
                <input type="password" name="password" class="form-control" placeholder="New Password" required="required">
                <?php
                        if(isset($error_stmnt) && $error_num == 1 && $error_stmnt != "") {
                            echo $error_stmnt;
                        }
                ?>
                <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required="required">
                <?php
                        if(isset($error_stmnt) && $error_num == 2 && $error_stmnt != "") {
                            echo $error_stmnt;
                        }
                ?>
                <input type="submit" class="submit-btn" value="Confirm Password">
            </form>
        </div>
    </body>
</html>