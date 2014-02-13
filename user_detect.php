<?php

	include 'connect_db.php';

	$username=htmlentities($_POST['username']);
	$password=$_POST['password'];
	//$password=md5($password);

	$query="select username,type from account where username='$username' and password='$password' ";
	$query_run=mysqli_query($conn,$query) or die('matching query not running');
	$query_num_rows=mysqli_num_rows($query_run);

	if($query_num_rows==1)
	{
		$query_row=mysqli_fetch_assoc($query_run);
		session_start();
		session_regenerate_id();
		$_SESSION['username'] = $query_row['username'];
		$_SESSION['type'] = $query_row['type'];
		if($_SESSION['type']=='student')
			$redirect = "student.php";
		elseif($_SESSION['type']=='professor')
			$redirect = "professor.php" ;
		elseif($_SESSION['type']=='admin')
			$redirect = "admin.php" ;
	}
	else
	{
		$redirect = "login.html";
	}	
	header("Location:$redirect");
	exit;

?>