<?php
	session_start() ;
	if(isset($_SESSION['username'])) {
		if(isset($_POST['username'], $_POST['picture'])) {
			include 'image_resize.php' ;
			include 'connect_db.php';
			extract($_POST) ;
			$type = $_SESSION['type'] ;
			$uploaddir = "pictures/";
			$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

			if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
			    $ext = pathinfo($uploadfile,PATHINFO_EXTENSION) ;
			    $name = time() ;
			    $path = "pictures/$name.$ext" ;
				rename($uploadfile,"$path") ;
				$type=='student' ? $table='student_profile' : $table='professor_profile' ;
				$query = "select picture from $table where username='$username'" ;
				$query_run = mysqli_query($conn, $query) or die('Delete picture query not working') ;
				$result = mysqli_fetch_assoc($query_run) ;
				if(!empty($result['picture']))
					unlink($result['picture']);
				$query = "update $table set picture='pictures/$name.jpeg' where username='$username'" ;
				$query_run = mysqli_query($conn, $query) or die('Update picture query not running') ;
				$dst = resize_image("$path",168,168) ;
				imagejpeg($dst, "pictures/$name.jpeg");
			    echo "pictures/$name.jpeg";
			    unlink($path);
			} else {
			    echo "Possible file upload attack!\n";
			}

		}
	}



?>