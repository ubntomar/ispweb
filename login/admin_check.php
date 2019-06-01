<?php
session_start();
	include("db.php");
	 	$con=mysqli_connect($server, $db_user, $db_pwd,$db_name) //connect to the database server
	or die ("Could not connect to mysql because ".mysqli_error());

	mysqli_select_db($con,$db_name)  //select the database
	or die ("Could not select to mysql because ".mysqli_error());
	
	//prevent sql injection
	$username=mysqli_real_escape_string($con,$_POST["username"]);
	$password=mysqli_real_escape_string($con,$_POST["password"]);
		
		//prevent xss

$username = htmlspecialchars($username,ENT_COMPAT);
$password =  htmlspecialchars($password,ENT_COMPAT);

	if($username==$admin_user)
	{
	if($password==$admin_password)
	{
	echo json_encode( array('result'=>1));
		//echo 1;  //return success
		$_SESSION['admin'] = true;		
	}
	else
	{
	echo json_encode( array('result'=>$msg_admin_pwd));
	//echo "Incorect password";
	}
	}
	else
	{
	echo json_encode( array('result'=>$msg_admin_user));
	//echo "Username Doesn't exist";
	}
	
	
	

?>