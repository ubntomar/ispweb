<?php
include("db.php");
         $con=mysqli_connect($server, $db_user, $db_pwd,$db_name) //connect to the database server
         or die ("Could not connect to mysql because ".mysqli_error());
         
         mysqli_select_db($con,$db_name)  //select the database
         or die ("Could not select to mysql because ".mysqli_error());
		 
		 
		 $selectall = "select * from admin_settings LIMIT 1";
         $result = mysqli_query($con,$selectall);
         if(!$result){ //table not created
		 
// create settings table

$query = "CREATE TABLE `admin_settings` (
  `admin_configured` tinyint(1) NOT NULL DEFAULT '0',
  `admin_logotxt` varchar(50) NOT NULL,
  `admin_email_from` varchar(100) NOT NULL,
  `admin_userid` varchar(50) NOT NULL,
  `admin_password` varchar(200) NOT NULL,
  `admin_site_url` varchar(300) NOT NULL,
  `admin_twitter_enable` tinyint(1) NOT NULL,
  `admin_twitter_consumer_key` varchar(100) NOT NULL,
  `admin_twitter_consumer_secret` varchar(100) NOT NULL,
  `admin_google_enable` tinyint(1) NOT NULL,
  `admin_google_client_id` varchar(100) NOT NULL,
  `admin_google_email` varchar(200) NOT NULL,
  `admin_google_clientsecret` varchar(200) NOT NULL,
  `admin_google_apikeys` varchar(200) NOT NULL,
  `admin_facebook_enable` tinyint(1) NOT NULL,
  `admin_facebook_appid` varchar(100) NOT NULL,
  `admin_facebook_secret` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
$result = mysqli_query($con, $query);
if ($result === false) die();
//else echo "<br>completed creating table: &quot;" . "user_settings" . "&quot;<br><br>Please go back\n";
 header('Location: ' . $url."/admin_settings.php");
		 }
		 else
		 {
			echo "Table exists already."; 
		 }
?>