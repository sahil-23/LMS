<?php
	session_start();
	if(!isset($_SESSION['username'])) {
		header('Location:login.php') ;
		exit ;
	}
	else {
		if(!isset($_POST['assgn_id'])) {
			// Add what to do when no assignment id is specified
		} 
		else {
?>

<!DOCTYPE html>
<html>
<head>
	<title>Submit Assignment</title>
	<link type="text/css" rel="stylesheet" href="header.css"/>
	<link type="text/css" rel="stylesheet" href="css/bootstrap.css"/>
</head>
<body>

<?php			
			include 'connect_db.php' ;
			include 'header.php' ;

			$assgn_id = $_POST['assgn_id'] ;
			$username = $_SESSION['username'] ;
			$query = "select submission_id from submission where username='$username'" ;
			$query_run = mysqli_query($conn, $query) ;
			$num_rows = mysqli_num_rows($query_run) ;
			if($num_rows==0) {
				$query = "insert into submission (`submission_id`, `assignment_id`, `username`, `timestamp`) VALUES (NULL, '$assgn_id', '$username', CURRENT_TIMESTAMP) ";
				$query_run = mysqli_query($conn, $query) or die('insert query not executed') ;
				$submission_id = mysqli_insert_id($conn) ;
			}
			else {
				$result = mysqli_fetch_assoc($query_run) ;
				$submission_id = $result['submission_id'];
				$query = "update submission set timestamp=CURRENT_TIMESTAMP" ;
				$query_run = mysqli_query($conn, $query);
			}

			$uploaddir = "submissions/$assgn_id/";
			$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
			$username = $_SESSION['username'] ;

			if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
			    $ext = pathinfo($uploadfile,PATHINFO_EXTENSION) ;
				rename($uploadfile,"submissions/$assgn_id/$submission_id.$ext") ;
				$query = "update submission set file='submissions/$assgn_id/$submission_id.$ext' where submission_id=$submission_id" ;
				$query_run = mysqli_query($conn, $query) or die('Insert file query not running') ;
			    echo "Assignment Successfully Submitted.\n";
			} else {
			    echo "Possible file upload attack!\n";
			}
		}
	}

?>
</body>
</html>