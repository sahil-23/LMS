<?php
	session_start();
	if(!isset($_SESSION['username'])) {
		header('Location:login.php') ;
		exit;
	}
	else {
		if(!isset($_POST['name'], $_POST['description'])) {
			echo "Error" ;
		}
		else {

			include 'connect_db.php' ;
			extract($_POST) ;
			$username = $_SESSION['username'] ;
			$query = "select group_id from `group` where name='$name'" ;
			$query_run = mysqli_query($conn, $query) or die('Check group query not running');
			if(mysqli_num_rows($query_run)!=0) {
				$a = mysqli_fetch_assoc($query_run) ;
				$b = $a['group_id'];
				echo "Group with same name already exists";
			}
			else {
				$query = "insert into `group` (`group_id`, `name`, `description`, `owner_username`, `status`) values (NULL, '$name', '$description', '$username', '0')" ;
				$query_run = mysqli_query($conn, $query) or die('Insert Group Query not running');
				$group_id = mysqli_insert_id($conn) ;
				$query = "insert into membership (`group_id`, `username`) values ('$group_id', '$username') " ;
				$query_run = mysqli_query($conn, $query) or die('Insert Member Query not running');
				echo "Request Sent To Admin To Create Group";
			}
		}
	}
?>