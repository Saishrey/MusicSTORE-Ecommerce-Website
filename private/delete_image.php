<?php

    require "../private/autoload.php";

	if(isset($_POST['delete'])){
        $arr['email'] = $_SESSION['email'];
        $arr['img_name'] = null;

        $file_pointer = "uploads/".$_SESSION['img_name'];

        if(!unlink($file_pointer)) { 
            echo "<script>
                alert('Error deleting image.');
                window.location.replace('../public/update.php');
                </script>";
        } 

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
?>