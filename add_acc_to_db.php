<?php
	session_start();
	include 'connect_db.php';
	include 'header.php';
	$num=$_POST['num'];	

	for($i=1; $i<=$num; $i++)
	{
		$name="name$i";
		$name=$_POST[$name];
		$firstname="firstname$i";
		$firstname=$_POST[$firstname];
		$lastname="lastname$i";
		$lastname=$_POST[$lastname];
		$type="type$i";
		$type=strip_tags($_POST[$type]);
		//echo "$type ";
		$query_to_match_username="SELECT username,type from account where username='$name' and type='$type'";
		$query_run=mysqli_query($conn,$query_to_match_username) or die('matching query not running');
		$query_run_rows=mysqli_num_rows($query_run);
		if($query_run_rows)
		{
			$redirect="add_account.php?msg=1&un='$name'";
			header("Location:$redirect");
			exit;
		}
		$query_to_insert="INSERT INTO  `viroom`.`account` (`username` ,`password` ,`type`)VALUES ('$name',  'pass',  '$type')";
		$query_run=mysqli_query($conn,$query_to_insert) or die('inserting query not running');
		
		if($type=='student'){
			$query_to_insert="INSERT INTO  `viroom`.`student_profile` (`username` ,`firstname`,`lastname` )VALUES ('$name',  '$firstname', '$lastname')";
			$query_run=mysqli_query($conn,$query_to_insert) or die('inserting query not running');
			$redirect="admin.php?";
			header("Location:$redirect");
			exit;
		
		}
		else{
			$query_to_insert="INSERT INTO  `viroom`.`professor_profile` (`username` ,`firstname`,`lastname` )VALUES ('$name',  '$firstname', '$lastname')";
			$query_run=mysqli_query($conn,$query_to_insert) or die('inserting query not running');
			$redirect="admin.php";
			header("Location:$redirect");
			exit;
		
		}
		//echo $query_run;
		//$query_num_rows=mysqli_num_rows($query_run);

	}
?>