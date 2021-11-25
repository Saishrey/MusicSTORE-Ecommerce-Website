<?php
    function check_login($con) {
        if(isset($_SESSION['user_id'])) {
            $arr['user_id'] = $_SESSION['user_id'];

            $query = "select * from customer where user_id = :user_id limit 1";
            $stmnt = $con->prepare($query);
            $check = $stmnt->execute($arr);
            
            if($check) {
                $data = $stmnt->fetchAll(PDO::FETCH_OBJ);
                if(is_array($data) && count($data) > 0) {
                    return $data[0];
                }
            }
        }

        //redirect to login
        // header("Location: login.php");
        // die;
    }

    function get_random_string($length) {

        $array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9,'A', 'B', 
                       'C', 'D', 'E', 'F', 'G', 'H', 
                       'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 
                       'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 
                       'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f', 
                       'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 
                       'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 
                       'w', 'x', 'y', 'z');

        $text = "";

        $length = rand(4, $length);

        for ($i=0; $i < $length; $i++) {
            $random = rand(0,61);

            $text .= $array[$random];
        }

        return $text;
    }

    function esc($word) {
        return addslashes($word);
    }

    function sendOTP($email, $otp, $string) {     

        require "Mail/phpmailer/PHPMailerAutoload.php";
        $mail = new PHPMailer;
    
        $mail->isSMTP();
        $mail->Host='smtp.gmail.com';
        $mail->Port=587;
        $mail->SMTPAuth=true;
        $mail->SMTPSecure='tls';

        $mail->Username='your_email_address'; // place your email address
        $mail->Password='your_password'; // place your password

        $mail->setFrom('your_email_address', 'OTP Verification'); // place your email address
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject="Your verify code";

        $mail->Body = "<p>Your One Time Password for ".$string." is:</p>
                         <br/><br/><h2>$otp</h2>";

        return $mail->send();
    }
?>