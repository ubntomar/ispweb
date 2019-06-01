<?php
include "db.php";

$con = mysqli_connect($server, $db_user, $db_pwd, $db_name) //connect to the database server
 or die("Could not connect to mysql because " . mysqli_error());
mysqli_query($con, "SET NAMES 'utf8'");
mysqli_select_db($con, $db_name) //select the database
 or die("Could not select to mysql because " . mysqli_error());

//prevent sql injection
$username = mysqli_real_escape_string($con, $_POST["username"]);
$password = mysqli_real_escape_string($con, $_POST["password"]);
$email = mysqli_real_escape_string($con, $_POST["email"]);

//prevent xss
$username = htmlspecialchars($username,ENT_COMPAT);
$password =  htmlspecialchars($password,ENT_COMPAT);
$email =  htmlspecialchars($email,ENT_COMPAT);

//check if user exist already
$query = "select * from " . $table_name . " where username='$username'";
$result = mysqli_query($con, $query) or die('error');
if (mysqli_num_rows($result)) {
    die($msg_reg_user);
}
//check if user exist already
$query = "select * from " . $table_name . " where email='$email'";
$result = mysqli_query($con, $query) or die('error');
if (mysqli_num_rows($result)) {
    die($msg_reg_email);

}

$activ_key = sha1(mt_rand(10000, 99999) . time() . $email);

if (phpversion() >= 5.5) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
} else {
    $hashed_password = crypt($password, '987654321'); //Hash used to suppress  PHP notice
}

$query = "insert into " . $table_name . "(username,password,email,activ_key) values ('$username','$hashed_password','$email','$activ_key')";

if (!mysqli_query($con, $query)) {
    die('Error: ' . mysqli_error());

}

//send email for the user with password

$to = $email;
$subject = "New Registration";
$body = "Hi " . $username .
    "<br /><br /> Thanks for your registration.<br />" .
    "Click the below link to activate your account<br /><br />" .
    "<a href=\"$url/activate.php?k=$activ_key\"> Activate Account </a><br /><br /> Thanks<br />";

$headers = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= "From:" . $from_address . "\r\n";

mail($to, $subject, $body, $headers);
echo $msg_reg_activ;
