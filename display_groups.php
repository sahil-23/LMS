<?php
		session_start();

	if(!isset($_SESSION['username'])) {
		header("Location:login.php");
		exit;
	}
	else {
		if(!isset($_GET['username'])) {
			//Add what to do when no username provided
		}
		else {
			include 'connect_db.php';
			$username = $_GET['username'];

			$group_id = "select name from `viroom`.`group` where status='1'";
			$group_id_query=mysqli_query($conn,$group_id) or die('group_id query for returning groups form group table not running');	
			$result=mysqli_fetch_all($group_id_query,MYSQLI_ASSOC);
		
?>
<html>
<head>
	<title>All Groups</title>
	<link type='text/css' rel='stylesheet' href='header.css'/>
	<link type='text/css' rel='stylesheet' href='css/bootstrap.css'/>
</head>
<body>
<?php 
			include 'header.php';
?>
<div class='container'>
<?php
			if($username==$_SESSION['username'])
				echo "<h4>These are the active groups:</h4>" ;
			else
				echo "<h4>$username is a member of these groups:</h4>" ;
			$i=0;
			$numberofrows=mysqli_num_rows($group_id_query);
			echo "<ol>" ;
			while($i<$numberofrows)
			{
				$group_name=$result[$i]['name'];
				$query = "select group_id from `group` where name='$group_name'" ;
				$ret = mysqli_query($conn,$query)	or die('Group id query not running') ;
				$gid = mysqli_fetch_object($ret) ;
?>
			<li>
				<a href=group.php?gid=<?php echo "$gid->group_id";?>>
					<?php echo "$group_name"?>
				</a>
			</li>
<?php				$i++;
			}
			echo "</ol>" ;
		}
	}
	?>
</div>
</body>
</html>