<?php
	session_start() ;
	if(!isset($_SESSION['username'])) {
		header('Location:login.php') ;
		exit ;
	}
	else {
?>
<!DOCTYPE html>
<html>
<head>
	<title>Assignment</title>
	<link type="text/css" rel="stylesheet" href="header.css"/>
	<link type="text/css" rel="stylesheet" href="css/bootstrap.css"/>
	<link type="text/css" rel="stylesheet" href="assignment.css"/>
</head>
<body>
<?php
		include 'connect_db.php' ;
		include 'header.php' ;
		if(!isset($_GET['assgn_id'])) {
			// Add what to do when no assignment id is provided
		}
		else {
			$assgn_id = $_GET['assgn_id'] ;
			$query = "select * from assignment where assignment_id=$assgn_id" ;
			$query_run = mysqli_query($conn, $query) ;
			if(mysqli_num_rows($query_run)==1) {
				$result = mysqli_fetch_assoc($query_run) ;
				extract($result) ;
				$query = "select firstname,lastname from professor_profile where username='$username'" ;
				$query_run = mysqli_query($conn, $query) ;
				$creater = mysqli_fetch_assoc($query_run) ;
				extract($creater) ;
				echo "<div id='assignment'>" ;
				echo "<h3>$heading</h3>";
				echo "<p>$text</p>" ;
				if(!empty($file)) {
					$file_name = basename($file) ;
					echo "<a href='$file' download>$file_name</a><br/>" ;
				}
				$query = "select name from `group` where group_id=$group_id" ;
				$query_run = mysqli_query($conn,$query) ;
				$result_group = mysqli_fetch_assoc($query_run);
				$group = $result_group['name'] ;
				echo "<p style='font-size:80%;'>Posted By: $firstname $lastname on <i>$timestamp</i> in $group</p></div>" ;
				if($_SESSION['type']=='student') {

?>
<form enctype="multipart/form-data" action="submit_assignment.php" method="POST" >
    <!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
    <!-- Name of input element determines name in $_FILES array -->
    <input type="hidden" name='assgn_id' value='<?php echo $assgn_id; ?>'>
    <div class='form-group'>
    	<input name="userfile" type="file" class='form-control' required/>
    </div>
    <div class='form-group'>
    	<input type="submit" class='btn btn-primary' value="Submit Assignment" />
    </div>
</form>
<?php 
				}
				else {
					if($username==$_SESSION['username']) {
?>
<div id='submission'>
<h3 id='submissions'>Submissions</h3>
<ol>
<?php
						$query = "select * from submission where assignment_id=$assgn_id" ;
						$query_run = mysqli_query($conn, $query) or die('Select submission not running') ;
						$result = mysqli_fetch_all($query_run,MYSQLI_ASSOC) ;
						$num_rows = mysqli_num_rows($query_run) ;
						if($num_rows!=0) {
							for($i=0 ; $i<$num_rows ; $i++)	{
								extract($result[$i]) ;
								$query = "select firstname, lastname from student_profile where username='$username'" ;
								$query_run = mysqli_query($conn, $query) or die('submitted by query not running');
								$name = mysqli_fetch_assoc($query_run) ;
								extract($name) ;
								echo "<li><a href='$file' target='_blank' download>$username</a><span> submitted on $timestamp by  $firstname $lastname</span></li>" ;
							}
						}
						else {
							echo "No submissions yet" ;
						}
					}

				}
			}	
		}
	}	
?>
</ol>
</div>
</body>
</html>