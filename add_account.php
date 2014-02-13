<?php
	session_start();
	if(!isset($_SESSION['username'])){
		header("Location:login.php");
	}
	else{
			include 'header.php';
			if(isset($_GET['msg']))
			{
				$error_code=$_GET['msg'];
				$existing_name=$_GET['un'];
				echo "$existing_name already exists...please use another name</br>";
				echo "The users added after $existing_name have been ignored ... please add them again";
			}	

?>
			<html>
			<head>
				<title>Add Account</title>
				<link type="text/css" rel="stylesheet" href="header.css"/>
				<link type="text/css" rel="stylesheet" href="css/bootstrap.css"/>
			    <script src="addInput.js" language="Javascript" type="text/javascript"></script>
			</head>
			
			<body>
			<div class='container'>
			<h4>Add New Accounts</h4>
			<form onsubmit="return add_account(this)" class='form-inline'>
				<div class='row'>
					<div class='col-md-4'>
						<input id="num" type="text" name="num" class='form-control' placeholder='Type the number of accounts to create' required/>
					</div>
						<input id="submit" class='btn btn-primary' type="submit" value="Next">
				</div>
			</form>

			<script type="text/javascript">
				function add_account(ele)
				{
					var num=ele.getElementsByTagName('input')[0].value;
					var type=ele.getElementsByTagName('input')[1].value;
					//document.getElementById('postsec').innerHTML=postal;	
					generate_form(num,type);
					return false;
				}

				function generate_form(num,type)
				{
					var ct=0;
					var form1="<form id=acc_form' action='add_acc_to_db.php' class='form-inline' method='post'><input type='hidden' id ='num' name='num' value='"+num+"''>";
					for (var i = 0; i < num; i++) 
					{
						ct++;					
						form1+="<div class='form-group'><label for='name"+ct+"'>username "+ct+"</label><input name='name"+ct+"' class='form-control' type='text'></div> <div class='form-group'><label for='firstname'>firstname</label><input name='firstname"+ct+"' class='form-control' type='text'></div> <div class='form-group'><label for='lastname'>lastname</label> <input name='lastname"+ct+"' class='form-control' type='text'></div> <div class='form-group'><label for=type'>type</label> <select name='type"+ct+"' class='form-control'> <option value='professor'>Professor</option><option value='student'>Student</option></select></div>";
					};
					form1+="</br><div class='form-group'><input type='submit' class='btn btn-primary' value='Add'></div></form>";
					var some=document.createElement('div');
					some.innerHTML=form1;
					document.getElementById('add_acc_space').appendChild(some);
					
					

				}
			</script>

<div id="add_acc_space">
</div>
			</body>
			

<?php	}
		
		
?>