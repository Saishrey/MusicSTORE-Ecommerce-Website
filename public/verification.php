<?php
    require "../private/autoload.php";
    $error_stmnt = "";

    if(isset($_POST["verify"])) {
        $otp = $_SESSION["otp"];
        $email = $_SESSION["email"];
        $otp_code = $_POST['otp_code'];

        if($otp != $otp_code) {
            $error_stmnt .= "<p style='color:#F78812; font-size:14px'>";
            $error_stmnt .= "<i class='fa fa-exclamation-circle'></i> ";
            $error_stmnt .= "OTP does not match.";
            $error_stmnt .= "</p>";
        }
        else {
            
            if($_SESSION['new_password']) {
                header("Location: newpassword.php");
                die;
            }
            if($_SESSION['deactivate_account']) {
                header("Location: deactivate.php");
                die;
            }
            //save to database
            $arr['user_id'] = $_SESSION['user_id'];
            $arr['user_name'] = $_SESSION['user_name'];
            $arr['email'] = $_SESSION['email'];
            $arr['password'] = $_SESSION['password'];
            $query = "insert into customer (user_id, user_name, email, password) values (:user_id,:user_name,:email,:password)";
            $stmnt = $con->prepare($query);
            $stmnt->execute($arr);

            unset($_SESSION['password']);
            unset($_SESSION['email']);
            unset($_SESSION['is_verified']);
            unset($_SESSION['user_id']);
            unset($_SESSION['user_name']);

            echo "<script>
                  alert('Account verified successfully.');
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
        <title>OTP Verification | MUSICSTORE</title>
    </head>
    <body>
        <!-- <style>
            #box {
                height: 450px;
            }
            .right-side img {
                height: 450px;
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
                /* background: rgba(24,24,24, 0.8); */
                /* background: url(images/large.jpg) no-repeat; */
                background: url(images/del_agent.jpg) no-repeat;
                background-size: center;
                font-family: 'Montserrat', sans-serif;
            }
            #box {
                display: flex;
                width: 500px;
                border: none;
                height: 470px;
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
        </style>
        <div id="box">
            <form method="post" class="form">
                <h2>Music<span style="color:#1b9bff;">STORE</span>&trade;</h2>
                <label for="OTP">OTP verification</label>
                <input type="text" class="form-control" name="otp_code" placeholder="Enter OTP" required="required">
                <?php
                    if(isset($error_stmnt) && $error_stmnt != "") {
                        echo $error_stmnt;
                    }
                ?>
                <input type="submit" class="submit-btn" value="Verify" name="verify">
            </form>
        </div>
    </body>
</html>