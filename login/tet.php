<?php
//session_start();
include "db.php";
$con = mysqli_connect("ispdevinstance.cvfeq4s0dnnx.us-east-1.rds.amazonaws.com", $db_user, $db_pwd, $db_name) //connect to the database server
 or die("Could not connect to mysql because " . mysqli_error());

mysqli_select_db($con, $db_name) //select the database
 or die("Could not select to mysql because " . mysqli_error());


?>

