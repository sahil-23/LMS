<?php

	session_start();
	if(!isset($_SESSION['username'])) {
		header("Location:login.php");
	}
	else {
?>
<!DOCTYPE html>
<html>
<head>
	<title>Profile</title>
	<link type='text/css' rel='stylesheet' href='header.css'/>
	<link type='text/css' rel='stylesheet' href='student_profile.css'/>
	<link type='text/css' rel='stylesheet' href='css/bootstrap.css'/>
</head>
<body>
<?php
		
		include 'connect_db.php' ;
		include 'header.php' ;
	
		if(!isset($_GET['username']))
			$username = $_SESSION['username'] ;
		else
			$username = $_GET['username'] ;
		$query = "select * from student_profile where username='$username'" ;
		$result = mysqli_query($conn, $query) ;
		$num_rows = mysqli_num_rows($result) ;
		if($num_rows==1) {
			$profile = mysqli_fetch_assoc($result) ;
			extract($profile) ;
			echo "<div id='profile'>" ;
			echo "<img src='$picture' class='clearfix'/>" ;
			echo "<div id='info'><table class='table table-hover table-bordered'>" ;
			echo "<tr>" ;
			echo "<td>Firstname</td> <td>$firstname</td>" ;
			echo "</tr>" ;
			echo "<tr>" ;
			echo "<td>Lastname</td> <td>$lastname</td>" ;
			echo "</tr>" ;
			echo "<tr>" ;
			echo "<td>Roll No.</td> <td>$roll_no</td>" ;
			echo "</tr>" ;
			echo "<tr>" ;
			echo "<td>Email id</td> <td>$email</td>" ;
			echo "</tr>" ;
			echo "<tr>" ;
			echo "<td>Branch</td> <td>$branch</td>" ;
			echo "</tr>" ;
			echo "<tr>" ;
			echo "<td>About</td> <td>$about</td>" ;
			echo "</tr>" ;
			echo "<tr>" ;
			echo "<td>Address</td> <td>$address</td>" ;
			echo "</tr>" ;
			echo "<tr>" ;
			echo "<td>Phone</td> <td>$phone_no</td>" ;
			echo "</tr>" ;
			echo "</table></div>" ;
		}

		if($_SESSION['username']==$username) {
			echo "<ul><li><a href='edit_student_profile.php' class='btn btn-primary btn-sm'>Edit Profile</a></li>" ;
			echo "<li><a href='change_password.php' class='btn btn-primary btn-sm'>Change Password</a></li></ul>";
			echo "</div>" ;
		}
		
	}
?>
</body>
</html>