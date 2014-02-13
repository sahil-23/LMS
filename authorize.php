<?php
	session_start() ;
	if(!isset($_SESSION['username'])) {
		header('Location:login.php') ;
		exit ;
	}
	else {
		if($_SESSION['type']=='student') {
			header('Location:student.php') ;
			exit;
		}
		elseif($_SESSION['type']=='professor') {
			header('Location:professor.php') ;
			exit;
		}
		elseif($_SESSION['type']=='admin') {
			include 'connect_db.php' ;
			if(isset($_GET['gid'])) {
				$gid = $_GET['gid'] ;
				$query = "update `group` set `status`=1 where group_id=$gid " ;
				$query_run = mysqli_query($conn, $query) or die('Update status query not running') ;
				echo "Group Created" ;
			}
			else {
?>
<html>
<head>
	<title>Authorize</title>
	<link type='text/css' rel='stylesheet' href='header.css'>
	<link type='text/css' rel='stylesheet' href='css/bootstrap.css'>
	<script type='text/javascript'>
		function authorize(ele) {
			var gid = ele.getAttribute('id') ;
			var xmlhttp = new XMLHttpRequest();				
			xmlhttp.onreadystatechange = function (){
				if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
					var parent = ele.parentNode;
					ele.remove();
					parent.innerHTML = xmlhttp.responseText ;
				}
			}
			xmlhttp.open('get', 'authorize.php?gid='+gid, true) ;
			xmlhttp.send() ;
		}
	</script>
</head>
<body>
<?php
				include 'header.php' ;
				echo "<div class='container'>";
				$query = "select * from `group` where status=0 ";
				$query_run = mysqli_query($conn, $query) or die('Select groups query not running') ;
				$result = mysqli_fetch_all($query_run, MYSQLI_ASSOC) ;
				$num_rows = mysqli_num_rows($query_run) ;
				if($num_rows==0) {
					echo "No groups left to authorize" ;
				}
				else {
					echo "<table>" ;
					for($i=0 ; $i<$num_rows ; $i++) {
						extract($result[$i]) ;
						$idx = $i+1;
						$query = "select type from account where username='$owner_username'" ;
						$type_query_run = mysqli_query($conn, $query) or die('type query not running');
						$type_query = mysqli_fetch_assoc($type_query_run) ;
						$type_query['type']=='student' ? $href='student_profile.php' : $href='professor_profile.php' ;
						echo "<tr>" ;
						echo "<td>$idx</td><td id='name$i'><a href='group.php?group_id=$group_id'>$name</a> created by <a href='$href?username=$owner_username'>$owner_username</a></td><td><button id='$group_id' class='btn btn-primary btn-xs' onclick='authorize(this)'>Authorize</button></td>" ;	
						echo "</tr>" ;
					}
					echo "</table>";
				}
			}
		}
	}
?>
</div>
</body>
</html>