<?php
	session_start();
	if(!isset($_SESSION['username'])) {
		header('Location:login.php') ;
		exit ;
	}
	else {
?>
<html>
<head>
	<title>Search</title>
	<link type='text/css' rel='stylesheet' href='search.css'>
	<link type='text/css' rel='stylesheet' href='header.css'>
	<link type='text/css' rel='stylesheet' href='css/bootstrap.css'>
</head>
<body>
<?php
		if(!isset($_GET['q'])) 
			$q = '' ;
		else 
			$q = $_GET['q'] ;

		include 'connect_db.php' ;
		include 'header.php' ;
?>
	<div id='search' class='container'>
		<form method='GET' action='search.php' class='form-inline'>
			<div class='row'>
				<div class='col-md-6'>
					<input type='text' name='q' class='form-control' placeholder='Type name to search'/>
				</div>
				<div class='form-group'>
					<input type='submit' class='btn btn-primary' value='Search'/>
				</div>
			</div>
		</form>
		<div id='group'>
			<h4>Groups</h4>
			<ol>
<?php
		$query_group = "select name, group_id from `group` where name LIKE '%$q%' " ;
		$query_run = mysqli_query($conn, $query_group) or die('Group search query not running') ;
		$num_rows = mysqli_num_rows($query_run) ;
		$groups = mysqli_fetch_all($query_run, MYSQLI_ASSOC) ;
		if($num_rows==0) {
			echo "No Groups Found." ;
		}
		for($i=0 ; $i<$num_rows ; $i++) {
			extract($groups[$i]) ;
			echo "<li><a href='group.php?gid=$group_id'>$name</a></li>" ;
		}
?>
			</ol>
		</div>
		<div id='student'>
			<h4>Students</h4>
			<ol>
<?php
		$query_student = "select firstname, lastname, username from student_profile where firstname LIKE '%$q%' " ;
		$query_run = mysqli_query($conn, $query_student) or die('Student search query not running') ;
		$num_rows = mysqli_num_rows($query_run) ;
		$groups = mysqli_fetch_all($query_run, MYSQLI_ASSOC) ;
		if($num_rows==0) {
			echo "No Students Found." ;
		}
		for($i=0 ; $i<$num_rows ; $i++) {
			extract($groups[$i]) ;
			echo "<li><a href='student_profile.php?username=$username'>$firstname $lastname</a></li>" ;
		}
?>
			</ol>
		</div>
		<div id='professor'>
			<h4>Professors</h4>
			<ol>
<?php
		$query_professor = "select firstname, lastname, username from professor_profile where firstname LIKE '%$q%' " ;
		$query_run = mysqli_query($conn, $query_professor) or die('Student search query not running') ;
		$num_rows = mysqli_num_rows($query_run) ;
		$groups = mysqli_fetch_all($query_run, MYSQLI_ASSOC) ;
		if($num_rows==0) {
			echo "No Professors Found." ;
		}
		for($i=0 ; $i<$num_rows ; $i++) {
			extract($groups[$i]) ;
			echo "<li><a href='professor_profile.php?username=$username'>$firstname $lastname</a></li>" ;
		}			
	}	
?>
			</ol>
		</div>
	</div>
</body>
</html>