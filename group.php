<?php
		session_start();

	if(!isset($_SESSION['username'])) {
		header("Location:login.php");
	}
	else {
?>
<html>
<head>
	<link type='text/css' rel='stylesheet' href='group.css'>
	<link type='text/css' rel='stylesheet' href='post.css'>
	<link type='text/css' rel='stylesheet' href='header.css'>
	<link type='text/css' rel='stylesheet' href='css/bootstrap.css'>
	<script type="text/javascript">
		function displaycomment(post_id)
		{

			xmlhttp = new XMLHttpRequest();
			
			xmlhttp.onreadystatechange =function (){
				if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
					var div = document.createElement('div') ;
					div.innerHTML = xmlhttp.responseText ;
					var post = document.getElementById(post_id) ;
					post.parentNode.replaceChild(div.childNodes[1],post) ;
				}
			}
			var c=document.getElementById(post_id).getElementsByTagName('input')[0].value;
			xmlhttp.open('GET','comment.php?comment_content='+c+'&pid='+post_id,true);
			xmlhttp.send();
			return false;
		}

	</script>
</head>
<body>
<?php 
	include 'header.php' ;
	if(!isset($_GET['gid'])) {
		//Add what to do when no group_id entered
	}
	else {
		include 'connect_db.php';
		$username = $_SESSION['username'];
		$group_id = $_GET['gid'];
		$group_name = "select name, description, status from `group` where group_id=$group_id";
		$group_name_query=mysqli_query($conn,$group_name) or die('group_name query for returning group names form groups in file group.php not running');
		$group_name_result=mysqli_fetch_assoc($group_name_query);
		if($group_name_result['status']==0) {
			echo "Group Not Yet Authorized" ;
		}
		else {
?>
<div id='enclose' class='clearfix'>
<div class='left'>
<div id='top'>
<h4>Name of group</h4>
<p>
<?php
			echo $group_name_result['name']."</p>";
			$description = $group_name_result['description'] ;
			echo "<h4>Description</h4><p>$description</p>" ;
			//echo $group_id.$username;
			$group_match = "select * from `viroom`.`membership` where group_id='$group_id' AND username='$username'";
			$group_run=mysqli_query($conn,$group_match);// or die('group_name query for returning group names form groups in file group.php not running');
			//$query_run_rows=$query_run->num_rows;
			$group_run_rows=mysqli_num_rows($group_run);
			$result=mysqli_fetch_assoc($group_run);
			//echo $group_run_rows;     //this is giving 1(which should be 0)
			//echo $result['group_id']; //this is not printing anything inspite of $query_run_rows=1
			if($group_run_rows==0)
			{
?>

<a href="join_group.php?gid=<?php echo $group_id;?>" class='btn btn-primary btn-sm'>Join Group</a>
<?php
			}
?>


<h4>Members</h4>
<ol>
<?php
			$members_query = "select username from membership where group_id=$group_id";
			$members_result = mysqli_query($conn,$members_query) or die('members_result query for returning member names form membership in file group.php not running');
			$num_rows = mysqli_num_rows($members_result) ;
			if($num_rows==0) {
				echo "No Members Yet" ;
			}
			while($members=mysqli_fetch_assoc($members_result)){
				$username = $members['username'] ;
				$query = "select type from account where username='$username'" ;
				$result = mysqli_query($conn, $query) ;
				$type = mysqli_fetch_assoc($result) ;
				if($type['type']=='student') {
					$query = "select firstname,lastname from student_profile where username='$username'";
					$href = "student_profile.php?username=$username" ;
				}
				elseif($type['type']=='professor') {
					$query = "select firstname,lastname from professor_profile where username='$username'";
					$href = "professor_profile.php?username=$username" ;
				}
				$result = mysqli_query($conn, $query) ;
				$name = mysqli_fetch_assoc($result) ;
				extract($name) ;
				echo "<li><a href=$href>$firstname $lastname </a></li>" ;
			}
			echo "</ol></div>" ;
			if($group_run_rows!=0)
			{	echo "<div id=bottom>";
				include 'insert_post.php';
				if($_SESSION['type']=='professor')
					include 'create_assignment.php' ;
				echo "</div>";
			}
?>
</div>
<div id='assignment_list' class='right'>
	<h4>List of Assignments</h4>
	<ol>
<?php
			$query = "select assignment_id,heading from assignment where group_id=$group_id" ;
			$query_run = mysqli_query($conn, $query) or die('assignment query not running') ;
			$result = mysqli_fetch_all($query_run,MYSQLI_ASSOC) ;
			$num_rows = mysqli_num_rows($query_run) ;
			if($num_rows==0) {
				echo "No Assignments Yet.";
			}
			for($i=0 ; $i<$num_rows ; $i++) {
				extract($result[$i]) ;
				echo "<li><a href='assignment.php?assgn_id=$assignment_id'>$heading</a></li>" ;
			}
?>
	</ol>
</div>
</div>
<div id='posts'>
<h4>Posts</h4>
<?php
			include 'post.php' ;
			$query = "select post_id from group_post where group_id=$group_id order by timestamp desc" ;
			$result = mysqli_query($conn, $query) or die('Cannot run query for post id') ;
			$post = mysqli_fetch_all($result,MYSQLI_ASSOC) ;
			$num_of_post = mysqli_num_rows($result) ;
			if($num_of_post==0) {
				echo "No Posts Yet." ;
			}
			for($i=0 ; $i<$num_of_post ; $i++) {
				display_post($conn, $post[$i]['post_id']) ;
			}	
		}	
	}
}
?>
</div>
</body>
</html>