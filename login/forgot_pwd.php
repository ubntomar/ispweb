<?php
include "db.php";
$con = mysqli_connect($server, $db_user, $db_pwd, $db_name) //connect to the database server
 or die("Could not connect to mysql because " . mysqli_error());

mysqli_select_db($con, $db_name) //select the database
 or die("Could not select to mysql because " . mysqli_error());

//prevent sql injection
$username = mysqli_real_escape_string($con, $_POST["username"]);
$email = mysqli_real_escape_string($con, $_POST["email"]);

$username = trim($username);
$email = trim($email);

if (!empty($username)) {
    if (!empty($email)) {
        $query = "select * from " . $table_name . " where username='$username' and email='$email'";
    } else {
        $query = "select * from " . $table_name . " where username='$username'";
    }

} else {
    $query = "select * from " . $table_name . " where email='$email'";
}

$result = mysqli_query($con, $query) or die('error');
$row = mysqli_fetch_array($result);
//update user's activation key with new key
$re_activ_key = sha1(mt_rand(10000, 99999) . time() . $email);
$activ_key = $row['activ_key'];

if (mysqli_num_rows($result)) {
    //Update the activation status to 2-Reset in progress and new activation key
    $query = "update " . $table_name . "	 set activ_status='2' , activ_key='$re_activ_key' where username='$username' and email='$email'";
    $result = mysqli_query($con, $query) or die('error');

    $to = $row['email'];
    $subject = "Password Reset";
    $body = "Hi " . $row['username'] .
        "<br />Your account password has been reset: <a href=\"$url/reset.php?k=$re_activ_key\"> Please Click to set a new password</a><br /> <br /> Thanks";
    $headers = "From:" . $from_address;
    $headers .= 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    mail($to, $subject, $body, $headers);
    //echo $body;
    echo "Please Check your Email for resetting your password";
    //header('Content-type: application/json');
    // echo json_encode( array('result'=>1,'txt'=>"Password has been successfully sent to your Email Address"));
} else {
    //echo json_encode( array('result'=>0,'txt'=>"User account doesn't Exist"));
    echo "User account doesn't Exist";
}
