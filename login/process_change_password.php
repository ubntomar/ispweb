<?php
session_start();
include "db.php";

$con = mysqli_connect($server, $db_user, $db_pwd, $db_name) //connect to the database server
 or die("Could not connect to mysql because " . mysqli_error());

mysqli_select_db($con, $db_name) //select the database
 or die("Could not select to mysql because " . mysqli_error());

//prevent sql injection
//$username=mysql_real_escape_string($_POST["username"]);
$oldpassword = mysqli_real_escape_string($con, $_POST["oldpassword"]);
$password = mysqli_real_escape_string($con, $_POST["password"]);
$username = $_SESSION['username'];

//check if user is having account
$query = "select * from " . $table_name . " where username='$username'";
$result = mysqli_query($con, $query) or die('error');
$row = mysqli_fetch_array($result);
$email = $row['email'];
$match = 0;
if (mysqli_num_rows($result)) {

    $dbpass = $row['password'];

    if (phpversion() >= 5.5) {
        if (password_verify($oldpassword, $dbpass)) {
            $match = 1;
            $pwd = password_hash($password, PASSWORD_DEFAULT);
        }

    } else {
        if (crypt($oldpassword, $dbpass) == $dbpass) {
            $match = 1;
            $pwd = crypt($password, '987654321');
        }
    }
    if ($match == 1) {

        //$pwd = crypt($password);
        $query = "update " . $table_name . "	 set password='$pwd' , activ_status=1 where username='$username'";
        $result = mysqli_query($con, $query) or die('error');

        //send email for the user with password

        $to = $email;
        $subject = "Password Reset";
        $body = "Hi " . $username .
            "<br /> Your new password is updated successfully<br />";

        $headers = "From:" . $from_address;
        $headers .= 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        mail($to, $subject, $body, $headers);
        echo "Password updated Successfully";
    } else {
        echo "password mismatch";
    }
} else {
    echo "Cannot change password:Username/password mismatch";
}
