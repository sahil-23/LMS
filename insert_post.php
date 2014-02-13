<?php
	if( !isset($_GET['post_content']) )
	{
?>
		<script type="text/javascript">
			function insertpost(ele)
			{
					xmlhttp = new XMLHttpRequest();
				
				xmlhttp.onreadystatechange = function (){
					if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
						var div = document.createElement('div') ;
						div.innerHTML = xmlhttp.responseText ;
						var post = document.getElementById('posts') ;
						post.insertBefore(div,post.childNodes[2]);
						ele.getElementsByTagName('input')[0].value = "";
					}
				}

				var postal=ele.getElementsByTagName('input')[0].value;
				//document.getElementById('postsec').innerHTML=postal;	
				xmlhttp.open('GET','insert_post.php?post_content='+postal+'&gid='+<?php echo $group_id ?>,true);
				xmlhttp.send();
				return false;
			
			}

		</script>

		<h4>Insert Post</h4>
		<form onsubmit="return insertpost(this)" class='form-inline'>
			<div class='row'>
				<div class='col-md-6'>
					<input id="post" class='form-control' type="text" name="post" placeholder='Insert Post' required/>
				</div>
					<input id="submit" class='btn btn-success' type="submit" value="post">
			</div>
		</form>

		
<?php
}
else
{	session_start();
	include 'connect_db.php';
	include 'post.php';
	$writer=$_SESSION['username'];
	$type=$_SESSION['type'];
	$group_id=$_GET['gid'];

	$post_content=$_GET['post_content'];
	$query = "INSERT INTO viroom.group_post (`post_id`, `timestamp`, `text`, `file`, `group_id`, `username`) VALUES (NULL, CURRENT_TIMESTAMP, '$post_content', '', $group_id, '$writer')";
	$query_run=mysqli_query($conn,$query) or die('insert post query not running');
	$query_postid=mysqli_insert_id($conn) ;

	display_post($conn, $query_postid);
}

?>