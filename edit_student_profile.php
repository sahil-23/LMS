<?php

	session_start();
	if(!isset($_SESSION['username'])) {
		header("Location:login.php");
	}
	else {
		include 'connect_db.php' ;
		if(!isset($_POST['firstname'])) {
?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit Profile</title>
	<link type='text/css' rel='stylesheet' href='header.css'/>
	<link type='text/css' rel='stylesheet' href='edit_profile.css'/>
	<link type='text/css' rel='stylesheet' href='css/bootstrap.css'/>
	<script type='text/javascript'>
		function upload_picture(ele) {
			var data = new FormData(ele) ;
			var xmlhttp = new XMLHttpRequest();
			
			xmlhttp.onreadystatechange = function (){
				if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
					var img = ele.parentNode.getElementsByTagName('img')[0] ;
					var picture = xmlhttp.responseText;
					img.setAttribute("src", picture) ;
					ele.getElementsByTagName('input')[3].value='';
				}
			}
			xmlhttp.open('post', 'upload_picture.php', true) ;
			xmlhttp.send(data)	;
		}		
	</script>
</head>
<body>
<?php
			include 'header.php' ;
			$username = $_SESSION['username'] ;
			$query = "select * from student_profile where username='$username'" ;
			$result = mysqli_query($conn, $query) ;
			$num_rows = mysqli_num_rows($result) ;

			if($num_rows==1) {
				$profile = mysqli_fetch_assoc($result) ;
				extract($profile) ;
				$str = "<form>
    				<input type='hidden' name='MAX_FILE_SIZE' value='10000000' />
    				<input type='hidden' name='username' value='$username'>
    				<input type='hidden' name='picture' value='$picture'>
    				<input name='userfile' onchange='upload_picture(this.form)' type='file' value='Upload Photo' required/>
					</form>" ;
				echo "<div id='profile' class='clearfix'>" ;
				echo "<div id='picture'><div id='img' class='clearfix'><img src='$picture'/></div>$str</div>" ;
				echo "<div id='info'><form action='edit_student_profile.php' method='POST'>" ;
				echo "<table class='table table-hover table-bordered'>" ;
				echo "<tr>" ;
				echo "<td>Firstname</td> <td><input type='text' name='firstname' value='$firstname' /></td>" ;
				echo "</tr>" ;
				echo "<tr>" ;
				echo "<td>Lastname</td> <td><input type='text' name='lastname' value='$lastname'/> </td>" ;
				echo "</tr>" ;
				echo "<tr>" ;
				echo "<td>Roll No.</td> <td><input type='text' name='roll_no' value='$roll_no'/> </td>" ;
				echo "</tr>" ;
				echo "<tr>" ;
				echo "<td>Email id</td> <td><input type='text' name='email' value='$email'/></td>" ;
				echo "</tr>" ;
				echo "<tr>" ;
				echo "<td>Branch</td> <td><input type='text' name='branch' value='$branch'/></td>" ;
				echo "</tr>" ;
				echo "<tr>" ;
				echo "<td>About</td> <td><textarea name='about'>$about</textarea></td>" ;
				echo "</tr>" ;
				echo "<tr>" ;
				echo "<td>Address</td> <td><input type='text' name='address' value='$address'/></td>" ;
				echo "</tr>" ;
				echo "<tr>" ;
				echo "<td>Phone</td> <td><input type='text' name='phone_no' value='$phone_no'/></td>" ;
				echo "</tr>" ;
				echo "</table>" ;
				echo "<input type='submit' class='btn btn-sm btn-primary' value='Submit'/>";
				echo "</form></div>";
			}
		}
		else {
			extract($_POST) ;
			$username = $_SESSION['username'] ;
			echo "$firstname $lastname $email" ;
			$query = "UPDATE `student_profile` SET `firstname`='$firstname',`lastname`='$lastname',`roll_no`='$roll_no',`email`='$email',`branch`='$branch',`about`='$about',`address`='$address', `phone_no`='$phone_no' WHERE username='$username' "; 
			$query_run = mysqli_query($conn, $query) or die('update query not working') ;
			header('Location:student_profile.php') ;
			exit ; 
		}

	}
?>
</body>
</html>