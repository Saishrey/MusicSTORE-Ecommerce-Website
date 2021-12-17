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

        $mail->setFrom('your_email_address', 'MusicSTORE.in OTP Verification'); // place your email address
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject="Your verify code";

        $mail->Body = "<p>Your One Time Password for ".$string." is:</p>
                         <br/><br/><h2>$otp</h2>";

        return $mail->send();
    }

    function deleteProfilePic($image_name) {
        $file_pointer = "uploads/".$image_name;

        if(!unlink($file_pointer)) { 
            echo "<script>
                alert('Error deleting image.');
                window.location.replace('../public/index.php');
                </script>";
        } 
    }

    function sendOrderPlacedEmail($email, $order_id, $delivery_date, $agent_contact) {     

        require "Mail/phpmailer/PHPMailerAutoload.php";
        $mail = new PHPMailer;
    
        $mail->isSMTP();
        $mail->Host='smtp.gmail.com';
        $mail->Port=587;
        $mail->SMTPAuth=true;
        $mail->SMTPSecure='tls';

        $mail->Username='your_email_address'; // place your email address
        $mail->Password='your_password'; // place your password

        $mail->setFrom('your_email_address', 'MusicSTORE.in'); // place your email address
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject='Your MusicSTORE order '.$order_id;

        $mail->Body = "<p>Your package will be delivered on <br>".$delivery_date."<br>
                        by our MusicSTORE Delivery Agent (Phone: ".$agent_contact.").</p>
                        <br><br>
                        To ensure your safety, the Delivery Agent will drop the package at your doorstep
                        , ring the doorbell and then move back 2 meters while waiting for you to collect your package.
                        If you are in containment zone, the agent will call you and request you to collect your package from the nearest
                        accessible point while following the same No-Contact delivery process.";

        return $mail->send();
    }

    function sendDeliveredEmail($email, $name) {     

        require "Mail/phpmailer/PHPMailerAutoload.php";
        $mail = new PHPMailer;
    
        $mail->isSMTP();
        $mail->Host='smtp.gmail.com';
        $mail->Port=587;
        $mail->SMTPAuth=true;
        $mail->SMTPSecure='tls';

        $mail->Username='your_email_address'; // place your email address
        $mail->Password='your_password'; // place your password

        $mail->setFrom('your_email_address', 'MusicSTORE.in'); // place your email address
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject='Delivered: Your MusicSTORE package has been delivered.';

        $mail->Body = "<p>Hi ".$name.",<br>
                        Your package has been delivered!</p>";

        return $mail->send();
    }
?>