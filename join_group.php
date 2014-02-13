<?php
	include 'connect_db.php';
	session_start();
	$group_id=$_GET['gid'];
	$username=$_SESSION['username'];
	echo $group_id;
	$query_to_join="INSERT into `viroom`.`membership` (`group_id`,`username`) values ('$group_id','$username')";
	$query_run=mysqli_query($conn,$query_to_join) or die('matching query not running');
	header("Location:group.php?gid=$group_id");
	?>
