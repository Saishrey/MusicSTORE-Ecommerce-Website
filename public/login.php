<?php

    require "../private/autoload.php";
    
    $email = "";
    $error_stmnt = "";
    $error_num = 0;
    $error = False;

    if($_SERVER["REQUEST_METHOD"] == "POST") {
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
        if((strlen($password) < 8 || strlen($password) > 16) && !$error) {
            $error_stmnt .= "<p style='color:#F78812; font-size:14px'>";
            $error_stmnt .= "<i class='fa fa-exclamation-circle'></i> ";
            $error_stmnt .= "Password must be 8-16 characters.";
            $error_stmnt .= "</p>";
            $error = True;
            $error_num = 2;
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
                    if($data->is_verified == 0) {
                        echo "<script>
                              alert('Please verify your email account before Sign in.');
                              </script>";
                    }
                    else if(password_verify($password, $password_hash)) {
                        $_SESSION['user_name'] = $data->user_name;
                        $_SESSION['user_id'] = $data->user_id;
                        header("Location: index.php");
                        die;
                    }
                }
            }
            $error_stmnt .= "<p style='color:#F78812; font-size:14px'>";
            $error_stmnt .= "<i class='fa fa-exclamation-circle'></i> ";
            $error_stmnt .= "Invalid Email or Password.";
            $error_stmnt .= "</p>";
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
                background: #181818;
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
                background: #181818;
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
                <a href="#" class="forgot-pass">Forgot password?</a>
                <hr>
                <a href="signup.php" class="sign-up">Create new account</a>
            </form>
        </div>
    </body>
</html>