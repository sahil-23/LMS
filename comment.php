<?php
session_start();
	include 'connect_db.php';
	include 'post.php';
	$writer=$_SESSION['username'];
	$type=$_SESSION['type'];
	$post_id=$_GET['pid'];
	$comment_content=$_GET['comment_content'];

	$query = "INSERT INTO viroom.comment (`comment_id`, `text`, `username`, `post_id`, `timestamp`) VALUES (NULL, '$comment_content','$writer','$post_id',CURRENT_TIMESTAMP )";
	$query_run=mysqli_query($conn,$query) or die('insert post query not running');
	//$query_num_rows=mysqli_num_rows($query_run);
	
	display_post($conn, $post_id);
?>


