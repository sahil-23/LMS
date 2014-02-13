<?php 

	if(!isset($_SESSION['username'])) {
		header('Location:login.php') ;
		exit ;
	}
	else {
?>

<header class='clearfix'>
<ul class='clearfix'>
<?php
		include 'connect_db.php' ;
		$uname = $_SESSION['username'] ;
		if($_SESSION['type']=='student')
			$query = "select firstname from student_profile where username='$uname'"	;
		elseif($_SESSION['type']=='professor')
			$query = "select firstname from professor_profile where username='$uname'" ;
		elseif($_SESSION['type']=='admin')
			$query = "select username from account where username='$uname'";
		
		$query_run=mysqli_query($conn,$query) or die('matching query not running') ;
		$query_num_rows=mysqli_num_rows($query_run) ;

		if($query_num_rows==1) {
			$query_row=mysqli_fetch_assoc($query_run) ;
			if($_SESSION['type']=='student') {	
				$href="student";
				$firstname = $query_row['firstname'] ;
			}
			elseif($_SESSION['type']=='professor'){
				$href="professor" ;
				$firstname = $query_row['firstname'] ;
			}
			else//if($_SESSION['type']=='admin')
				$href="admin" ;

			if($_SESSION['type']!='admin')
			{	
				echo "<li><a href='$href.php' class='btn btn-primary btn-xs'>Home</a></li>";
			//	echo "<li><a href='$href"."_profile.php?username=$uname'>$firstname</a></li>" ;
				if($firstname)
					echo "<li><a href='$href"."_profile.php' class='btn btn-primary btn-xs'>$firstname</a></li>" ;
				else
					echo "<li><a href='$href"."_profile.php' class='btn btn-primary btn-xs'>$uname</a></li>";
			}
		}
?>
	<li>
		<a href='search.php' class='btn btn-primary btn-xs'>Search</a>
	</li>
<?php	
			if($_SESSION['type']!='admin'){
?>
	<li>
		<a href='display_groups.php?username=<?php echo $uname;?>' class='btn btn-primary btn-xs'>Groups </a>
	</li>
<?php
			}
	}
?>
	<li>
		<a href='logout.php' class='btn btn-primary btn-xs'>Logout</a>
	</li>
</ul>
</header>
