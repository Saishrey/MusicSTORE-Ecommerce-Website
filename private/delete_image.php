<?php

    require "../private/autoload.php";

	if(isset($_POST['delete_user_dp'])){
        $arr['email'] = $_SESSION['email'];
        $arr['img_name'] = null;

        deleteProfilePic($_SESSION['img_name']);

        $query = "update customer set img_name=:img_name WHERE email=:email";
        $stmnt = $con->prepare($query);
        $result = $stmnt->execute($arr);
		if($result){
			// header("Location:../index.php?success=Image successfully deleted!");
            $_SESSION['img_name'] = null;
            echo "<script>
                alert('Image deleted successfully!');
                window.location.replace('../public/useraccount.php');
                </script>";
			exit(0);
		}else{
			// header("Location:../index.php?error=Invalid ImageId!");
            echo "<script>
                alert('Error deleting image.');
                window.location.replace('../public/update.php');
                </script>";
			exit(0);
		}
	}

    if(isset($_POST['delete_seller_dp'])){
        $arr['email'] = $_SESSION['email'];
        $arr['seller_dp'] = null;

        deleteProfilePic($_SESSION['seller_dp']);

        $query = "update seller set seller_dp=:seller_dp WHERE seller_email=:email";
        $stmnt = $con->prepare($query);
        $result = $stmnt->execute($arr);
		if($result){
			// header("Location:../index.php?success=Image successfully deleted!");
            $_SESSION['seller_dp'] = null;
            echo "<script>
                alert('Image deleted successfully!');
                window.location.replace('../public/selleraccount.php');
                </script>";
			exit(0);
		}else{
			// header("Location:../index.php?error=Invalid ImageId!");
            echo "<script>
                alert('Error deleting image.');
                window.location.replace('../public/updateseller.php');
                </script>";
			exit(0);
		}
	}
?>