<?php 
	function display_post($conn, $post_id) {
		$query = "select * from `group_post` where post_id=$post_id order by timestamp desc"	;
		$query_run = mysqli_query($conn, $query) or die('post query not running');
		$query_rows = mysqli_num_rows($query_run) ;
		if($query_rows==1) {
			$post = mysqli_fetch_assoc($query_run) ;
			extract($post) ;
		}

		$query = "select name from `group` where group_id=$group_id "	;
		$query_run = mysqli_query($conn, $query) or die('group query not running');
		$query_rows = mysqli_num_rows($query_run) ;
		if($query_rows==1) {
			$group = mysqli_fetch_assoc($query_run) ;
			extract($group) ;
		}

		$query = "select type from account where username='$username'" ;
		$query_run = mysqli_query($conn,$query) or die('type query not running');
		$query_rows = mysqli_num_rows($query_run) ;
		if($query_rows==1) {
			$user = mysqli_fetch_assoc($query_run) ;
			extract($user) ;
		}

		if($type=='student')
			$query = "select firstname,lastname from student_profile where username='$username'";
		elseif($type=='professor')
			$query = "select firstname,lastname from professor_profile where username='$username'";
		$query_run = mysqli_query($conn, $query) or die('post query not running');
		$query_rows = mysqli_num_rows($query_run) ;
		if($query_rows==1) {
			$post_creater = mysqli_fetch_assoc($query_run) ;
			extract($post_creater) ;
		}
?>
		<div class='post' id='<?php echo $post_id ?>'>
			<div class='main'>
			<div class='bar'>
<?php 
					$type=='student'? $href="student_profile.php?username=$username" : $href="professor_profile.php?username=$username" ;
					//Name of user who posted
					echo "<a href='$href'>$firstname $lastname</a> <span> > </span>" ;
					//Name of group
					echo "<a href='group.php?gid=$group_id'>$name </a>" ;
?>
			</div>
			<div class='content'>	
				<p>
<?php
					echo $text ;
?>
				</p>
			</div>
		</div>
			<div class='comments'>
			<h6><b>COMMENTS</b></h6>	
			<form onsubmit="return displaycomment(<?php echo $post_id; ?>)" action='comment.php' method='GET' class='form-inline'>
				<div class='row'>
				<div class='col-md-6'>
					<input name='comment' id='comment' class='form-control input-sm' type='text' placeholder='Add a comment' required/>
				</div>
				<div class='form-group'>
					<input id="submit" class='btn btn-primary btn-sm' type="submit" value="Comment" />
				</div>
			</div>
			</form>

<?php				
					$query = "select * from comment where post_id=$post_id order by timestamp DESC" ;
					$query_run = mysqli_query($conn, $query)  ;
					$comments = mysqli_fetch_all($query_run, MYSQLI_ASSOC) ;
					$cnum = mysqli_num_rows($query_run) ;
					if($cnum==0)
						echo 'no comments yet' ;
					else {
						$i = 0 ;
						while($i<$cnum) {
							extract($comments[$i]);
							$query = "select type from account where username='$username'" ;
							$query_run = mysqli_query($conn,$query) or die('type query not running');
							$query_rows = mysqli_num_rows($query_run) ;
							if($query_rows==1) {
								$user = mysqli_fetch_assoc($query_run) ;
								extract($user) ;
							}
							$type=='student'? $href="student_profile.php?username=$username" : $href="professor_profile.php?username=$username" ;
							if($type=='student')
								$query = "select firstname,lastname from student_profile where username='$username'";
							elseif($type=='professor')
								$query = "select firstname,lastname from professor_profile where username='$username'";
							$query_run = mysqli_query($conn, $query) or die('post query not running');
							$query_rows = mysqli_num_rows($query_run) ;
							if($query_rows==1) {
								$commentor = mysqli_fetch_assoc($query_run) ;
								extract($commentor) ;
							}
							echo "<ul><li><a href='$href'> $firstname $lastname </a></li>" ;
							echo "<li>$text</li></ul>" ;
							$i++;
						}
					}

?>
			</div>
		</div>

<?php
}
//function ends here
?>