<?php
include "db.php";

$con = mysqli_connect($server, $db_user, $db_pwd, $db_name) //connect to the database server
 or die("Could not connect to mysql because " . mysqli_error());

mysqli_select_db($con, $db_name) //select the database
 or die("Could not select to mysql because " . mysqli_error());

//prevent sql injection
$logotxt = mysqli_real_escape_string($con, $_POST["logotxt"]);
$admin_email_from = mysqli_real_escape_string($con, $_POST["admin_email_from"]);
$admin_userid = mysqli_real_escape_string($con, $_POST["admin_userid"]);
$admin_password = mysqli_real_escape_string($con, $_POST["admin_password"]);
$admin_site_url = mysqli_real_escape_string($con, $_POST["admin_site_url"]);
$admin_facebook_enable = mysqli_real_escape_string($con, $_POST["admin_facebook_enable"]);
$admin_facebook_appid = mysqli_real_escape_string($con, $_POST["admin_facebook_appid"]);
$admin_facebook_secret = mysqli_real_escape_string($con, $_POST["admin_facebook_secret"]);
$admin_twitter_enable = mysqli_real_escape_string($con, $_POST["admin_twitter_enable"]);
$admin_twitter_consumer_key = mysqli_real_escape_string($con, $_POST["admin_twitter_consumer_key"]);
$admin_twitter_consumer_secret = mysqli_real_escape_string($con, $_POST["admin_twitter_consumer_secret"]);
$admin_google_enable = mysqli_real_escape_string($con, $_POST["admin_google_enable"]);
$admin_google_client_id = mysqli_real_escape_string($con, $_POST["admin_google_client_id"]);
$admin_google_email = mysqli_real_escape_string($con, $_POST["admin_google_email"]);
$admin_google_clientsecret = mysqli_real_escape_string($con, $_POST["admin_google_clientsecret"]);
$admin_google_apikeys = mysqli_real_escape_string($con, $_POST["admin_google_apikeys"]);


//prevent xss
$logotxt = htmlspecialchars($logotxt,ENT_COMPAT);
$admin_email_from = htmlspecialchars($admin_email_from,ENT_COMPAT);
$admin_userid = htmlspecialchars($admin_userid,ENT_COMPAT);
$admin_password = htmlspecialchars($admin_password,ENT_COMPAT);
$admin_site_url = htmlspecialchars($admin_site_url,ENT_COMPAT);
$admin_facebook_enable = htmlspecialchars($admin_facebook_enable,ENT_COMPAT);
$admin_facebook_appid = htmlspecialchars($admin_facebook_appid,ENT_COMPAT);
$admin_facebook_secret = htmlspecialchars($admin_facebook_secret,ENT_COMPAT);
$admin_twitter_enable = htmlspecialchars($admin_twitter_enable,ENT_COMPAT);
$admin_twitter_consumer_key = htmlspecialchars($admin_twitter_consumer_key,ENT_COMPAT);
$admin_twitter_consumer_secret = htmlspecialchars($admin_twitter_consumer_secret,ENT_COMPAT);
$admin_google_enable = htmlspecialchars($admin_google_enable,ENT_COMPAT);
$admin_google_client_id = htmlspecialchars($admin_google_client_id,ENT_COMPAT);
$admin_google_email = htmlspecialchars($admin_google_email,ENT_COMPAT);
$admin_google_clientsecret = htmlspecialchars($admin_google_clientsecret,ENT_COMPAT);
$admin_google_apikeys = htmlspecialchars($admin_google_apikeys,ENT_COMPAT);


$query = "update admin_settings" ." set admin_configured=1,admin_logotxt='$logotxt' , admin_email_from='$admin_email_from',
admin_email_from='$admin_email_from',
admin_userid='$admin_userid',
admin_password='$admin_password',
admin_site_url='$admin_site_url',
admin_facebook_enable='$admin_facebook_enable',
admin_facebook_appid='$admin_facebook_appid',
admin_facebook_secret='$admin_facebook_secret',
admin_twitter_enable='$admin_twitter_enable',
admin_twitter_consumer_key='$admin_twitter_consumer_key',
admin_twitter_consumer_secret='$admin_twitter_consumer_secret',
admin_google_enable='$admin_google_enable',
admin_google_client_id='$admin_google_client_id',
admin_google_email='$admin_google_email',
admin_google_clientsecret='$admin_google_clientsecret',
admin_google_apikeys='$admin_google_apikeys'
 where admin_userid='$admin_userid'";
 //echo $query;
        $result = mysqli_query($con, $query) or die('error updating');
if($result){
	echo "settings saved";
}
else { echo "error";
}