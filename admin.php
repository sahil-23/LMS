<?php
	session_start();
	if(!isset($_SESSION['username'])) {
		header("Location:login.php");
	}
	else {
?>
<html>
<head>
	<title>Admin</title>
	<link type="text/css" rel="stylesheet" href="header.css"/>
	<link type="text/css" rel="stylesheet" href="css/bootstrap.css"/>
</head>

<body>
<?php
		include 'header.php' ;
		$username=$_SESSION['username'];
		$type = $_SESSION['type'] ;
		if($type=='student') {
			header("Location:student.php");
			exit;
		}
		elseif($type=='professor'){
			header("Location:professor.php");
			exit;	
		}
		else {
	//		include 'header.php' ;
		}
	}
?>
		<a href="add_account.php"> Add accounts </a></br>
		<a href="authorize.php" > Authorize group creation </a>
			
			
</body>
</html>