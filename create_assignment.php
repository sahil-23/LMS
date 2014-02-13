<?php
	if (session_status() == PHP_SESSION_NONE) {
 	   session_start();
	}
	if(!isset($_SESSION['username'])) {
		header('Location:login.php') ;
		exit ;
	}
	else{
		if(!isset($_POST['group_id'])) {
?>
<link rel='stylesheet' type='text/css' href='create_assignment.css'>
<script type='text/javascript'>
	function createAssignment(ele) {
		var data = new FormData(ele) ;
		var xmlhttp = new XMLHttpRequest();				
		xmlhttp.onreadystatechange = function (){
			if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
				var response = JSON.parse(xmlhttp.response) ;
				var div = document.getElementById('create_assignment') ;
				var p = div.getElementsByTagName('p')[0] ;
				p.innerHTML = response.msg ;
				var assgn_list = document.getElementById('assignment_list').getElementsByTagName('ol')[0] ;
				var li = document.createElement('li') ;
				li.innerHTML = response.assignment ;
				assgn_list.insertBefore(li,null) ;
			}
		}
		xmlhttp.open('post', 'create_assignment.php', true) ;
		xmlhttp.send(data)	;
		return false;	
	}
	
</script>

<div id='create_assignment'>
<h4>Create Assignment</h4>
<p></p>
<form onsubmit="return createAssignment(this)" class='form-horizontal'>
    <div class='form-group'>
    	<label for='heading' class='col-md-2 control-label'>Heading</label>
    	<div class='col-md-5'>
    		<input type='text' name='heading' id='heading' class='form-control' required/>
    	</div>
    </div>
    <div class='form-group'>
    	<label for='description' class='col-md-2 control-label'>Description</label>
    	<div class='col-md-5'>	
    		<textarea name='description' id='description' class='form-control' rows='3' required></textarea>
    	</div>
    </div>
    <div class='form-group' id='file'>
    	<div class='col-md-offset-2 col-md-5'>
    		<input name="userfile" class='form-control'type="file" />
    	</div>
    </div>
    <!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="10000000 " />
    <!-- Name of input element determines name in $_FILES array -->
    <input type="hidden" name='group_id' value='<?php echo $group_id ?>'>
    <div class='form-group'>
    	<div class='col-md-offset-2 col-md-5'>
    		<input type="submit" class='btn btn-success' value="Create Assignment" />
    	</div>
	</div>
</form>
</div>

<?php
		}
		else {
			function outputJSON($msg, $assignment){
			    header('Content-Type: application/json');
			    die(json_encode(array(
			        'msg' => $msg,
			        'assignment' => $assignment
			    )));
			}
			include 'connect_db.php' ;
			extract($_POST) ;
			$username = $_SESSION['username'] ;

			if(isset($group_id)){
				$description = nl2br($description) ;
				$query = "insert into `assignment` (`assignment_id`, `username`, `group_id`, `timestamp`, `text`, `heading`) VALUES (NULL, '$username', '$group_id',CURRENT_TIMESTAMP,'$description','$heading') ";
				$query_run = mysqli_query($conn, $query) or die('insert query not executed') ;
				$assignment_id = mysqli_insert_id($conn) ;
				mkdir("submissions/$assignment_id") ;
				$assignment = '';

				if($_FILES['userfile']['size']!=0) {
					if($_FILES['userfile']['size']<=10000000) {
						$uploaddir = "assignments/";
						$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

						if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
						    $ext = pathinfo($uploadfile,PATHINFO_EXTENSION) ;
							rename($uploadfile,"assignments/$assignment_id.$ext") ;
							$query = "update assignment set file='assignments/$assignment_id.$ext' where assignment_id=$assignment_id" ;
							$query_run = mysqli_query($conn, $query) or die('cannot execute update query') ;
							$msg = "Assignment Successfully Created." ;
							$assignment = "<a href='assignment.php?assgn_id=$assignment_id'>$heading</a>" ;
						} 
						else {
						    $msg = "Possible file upload attack!";
						}
					}
					else {
						$msg = 'Size of file is too large' ;
					}
				}
				else {
					$msg = 'Assignment Successfully Created.' ;
					$assignment = "<a href='assignment.php?assgn_id=$assignment_id'>$heading</a>" ;
				}

				outputJSON($msg, $assignment) ;
			}	
		}
	}


?>