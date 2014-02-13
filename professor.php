<?php
	session_start();
	if(!isset($_SESSION['username'])) {
		header("Location:login.php");
	}
	else {
?>
<html>
<head>
	<title>Professor</title>
	<link type="text/css" rel="stylesheet" href="header.css"/>
	<link type="text/css" rel="stylesheet" href="post.css"/>
	<link type="text/css" rel="stylesheet" href="home.css"/>
	<link type="text/css" rel="stylesheet" href="css/bootstrap.css"/>

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

		function create_group(ele) {
			var data = new FormData(ele) ;
			xmlhttp = new XMLHttpRequest() ;	
			xmlhttp.onreadystatechange =function (){
				if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
					var  message = document.getElementById('message');
					var response = xmlhttp.responseText;
					message.innerHTML = response ;
					ele.reset() ;
				}
			}
			xmlhttp.open('POST','create_group.php',true);
			xmlhttp.send(data);
			return false;
		}

	</script>

</head>
<body>
<?php
		$username=$_SESSION['username'];
		$type = $_SESSION['type'] ;
		
		if($type=='admin'){
			header("Location:admin.php");
			exit;
		}

		elseif($type=='student') {
			header("Location:student.php");
			exit;
		}
		else {
			include 'header.php' ;
			echo "<div class='my-container'><div class='row'><div id='feed' class='col-md-6'><h4>Feed</h4>";
			include 'post.php' ;
			
			$query = "select group_id from membership where username='$username'";
			$query_run = mysqli_query($conn,$query) or die ('In newsfeed - membership query not working');
			$no_of_groups = mysqli_num_rows($query_run) ;
			if($no_of_groups==0) {
				echo "Not yet a member of any group.<br/>Join some groups to know more about what's happening." ;
			}
			else {
				while($group_id = mysqli_fetch_object($query_run)){
					//echo $group_id;
					$posts="select post_id from group_post where group_id = '$group_id->group_id' order by timestamp desc";
					$posts_run = mysqli_query($conn,$posts) or die ('In newsfeed - group_post query not working');
					$no_of_posts = mysqli_num_rows($posts_run) ;
					//echo $no_of_posts;
					$num=2;
					while($post_id = mysqli_fetch_object($posts_run)){
						if($num>0){
							display_post($conn, $post_id->post_id);
							$num--;
						}
						else
							break;
					}
				}
			}
?>
</div>
<div class='col-md-6'>
<p id='message'></p>
<div id='create_group'>
	<h4>Create Group</h4>
	<form onsubmit='return create_group(this)' >
		<div class='form-group'>
	    	<label for='name'>Name</label>
	    	<input type='text' name='name' id='name' class='form-control' required/>
	    </div>
	    <div class='form-group'>
	    	<label for='description'>Description</label>
	    	<textarea name='description' id='description' class='form-control' rows='3' required></textarea>
	    </div>
	    <div class='form-group'>
	    	<input type='submit' value='Create Group' class='btn btn-primary' />
	    </div>
	</form>
</div>
<div id='groups'>
<?php
			$query = "select group_id from membership where username='$username'";
			$query_run = mysqli_query($conn,$query) or die ('In newsfeed - membership query not working');
			$no_of_groups = mysqli_num_rows($query_run) ;
			$username = $_SESSION['username'] ;
			$group_id = "select * from membership where username='$username'";
			$group_id_query=mysqli_query($conn,$group_id) or die('group_id query for returning groups form membership table not running');	
			$result=mysqli_fetch_all($group_id_query,MYSQLI_ASSOC);
			echo "<h4>You are a member of these groups:</h4>" ;
			if($no_of_groups==0) {
				echo "No Groups Joined Yet.";
			}
			else {
				$i=0;
				echo "<ol>" ;
				while($i<$no_of_groups)
				{
					$group_id=$result[$i]['group_id'];
					$query = "select name,status from `group` where group_id=$group_id" ;
					$ret = mysqli_query($conn,$query)	or die('Group name query not running') ;
					$gname = mysqli_fetch_assoc($ret) ;
					extract($gname) ;
					if($status==0) {
						echo "<li>$name Group yet to be authorized</li>" ;
					}
					else {
						echo "<li><a href='group.php?gid=$group_id'>";
						echo $gname['name'];
						echo "</a></li>";
					}
					$i++;
				}
				echo "</ol>" ;
			}
?>
</div>
</div>
</div>
</div>
<?php			
		}
	}
?>
</body>
</html>