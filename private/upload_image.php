<?php

    require "../private/autoload.php";

	if(isset($_POST['user_upload_image'])){
		$image = $_FILES['imageFile'];
		$imageName = $image['name'];
		$imageType = $image['type'];
		$imageTmp_name = $image['tmp_name'];
		$error = $image['error'];
		$imageSize = $image['size'];

		if(!$error){
			if($imageSize <= 5242880){
				$imageNewName = $_SESSION['user_id']."_profile_pic".$imageName;
				$destinationFolder = 'uploads/';
				$allowed = array("png","jpg","jpeg","JPG","PNG","JPEG");
				if(in_array(explode('/',$imageType)[1], $allowed)) {
					if(move_uploaded_file($imageTmp_name, $destinationFolder.$imageNewName)){
                        $arr['email'] = $_SESSION['email'];
                        $arr['img_name'] = $imageNewName;

                        $query = "update customer set img_name=:img_name WHERE email=:email";
                        $stmnt = $con->prepare($query);
                        $result = $stmnt->execute($arr);
						if($result) {
							// header("Location: update.php?success=File successfully uploaded!!");
							if($_SESSION['img_name'] != null) {
								deleteProfilePic($_SESSION['img_name']);
							} 
							$_SESSION['img_name'] = $imageNewName;
							echo "<script>
								alert('Image uploaded successfully!');
								window.location.replace('../public/useraccount.php');
								</script>";
						}else {
							header("Location: update.php?error=".$stmnt->errorInfo());
							exit(0);
						}
					}
					else {
							// header("Location: update.php?error=Make sure you have not any special characters in your file");
							echo "<script>
								alert('Make sure you do not have any special characters in your file name.');
								window.location.replace('../public/update.php');
								</script>";
					}
				}
				else{
					// header("Location: update.php?error=Please upload only png or jpg images");
					echo "<script>
						alert('Images can be of the type [png, jpg, jpeg, JPG, PNG, JPEG].');
						window.location.replace('../public/update.php');
						</script>";
				}
				
			}else{
				// header("Location: update.php?error=The size of image sould not increase 5mb");
				echo "<script>
					alert('The size of image should not increase 5MB.');
					window.location.replace('../public/update.php');
					</script>";
			}
		}else{
			// header("Location: update.php?error=NULL!");
			echo "<script>
				alert('Error uploading image.');
				window.location.replace('../public/update.php');
				</script>";
		}
		
	}

	if(isset($_POST['seller_upload_image'])){
		$image = $_FILES['imageFile'];
		$imageName = $image['name'];
		$imageType = $image['type'];
		$imageTmp_name = $image['tmp_name'];
		$error = $image['error'];
		$imageSize = $image['size'];

		if(!$error){
			if($imageSize <= 5242880){
				$imageNewName = $_SESSION['seller_id']."_profile_pic".$imageName;
				$destinationFolder = 'uploads/';
				$allowed = array("png","jpg","jpeg","JPG","PNG","JPEG");
				if(in_array(explode('/',$imageType)[1], $allowed)) {
					if(move_uploaded_file($imageTmp_name, $destinationFolder.$imageNewName)){
                        $arr['email'] = $_SESSION['email'];
                        $arr['seller_dp'] = $imageNewName;

                        $query = "update seller set seller_dp=:seller_dp WHERE seller_email=:email";
                        $stmnt = $con->prepare($query);
                        $result = $stmnt->execute($arr);
						if($result) {
							// header("Location: update.php?success=File successfully uploaded!!");
							if($_SESSION['seller_dp'] != null) {
								deleteProfilePic($_SESSION['seller_dp']);
							} 
							$_SESSION['seller_dp'] = $imageNewName;
							echo "<script>
								alert('Image uploaded successfully!');
								window.location.replace('../public/selleraccount.php');
								</script>";
						}else {
							header("Location: updateseller.php?error=".$stmnt->errorInfo());
							exit(0);
						}
					}
					else {
							// header("Location: updateseller.php?error=Make sure you have not any special characters in your file");
							echo "<script>
								alert('Make sure you do not have any special characters in your file name.');
								window.location.replace('../public/updateseller.php');
								</script>";
					}
				}
				else{
					// header("Location: updateseller.php?error=Please upload only png or jpg images");
					echo "<script>
						alert('Images can be of the type [png, jpg, jpeg, JPG, PNG, JPEG].');
						window.location.replace('../public/updateseller.php');
						</script>";
				}
				
			}else{
				// header("Location: updateseller.php?error=The size of image sould not increase 5mb");
				echo "<script>
					alert('The size of image should not increase 5MB.');
					window.location.replace('../public/updateseller.php');
					</script>";
			}
		}else{
			// header("Location: updateseller.php?error=NULL!");
			echo "<script>
				alert('Error uploading image.');
				window.location.replace('../public/updateseller.php');
				</script>";
		}
		
	}

?>
