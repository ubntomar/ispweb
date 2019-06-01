

	<!DOCTYPE html>
<head>
    <title>PHP Login System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
	    <link href="css/style.css" rel="stylesheet" media="screen">
</head>

<body>
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery.validate.js"></script>
    <div class="logo">
         <h2><?php include 'db.php';
echo $logotxt;?></h2>

    </div>
    <form class="form-horizontal" id="login_form">
         <h2>User Activation</h2>

        <div class="line"></div>

<div class="messagebox">
            <div id="alert-message">


<?php
include "db.php";
$con = mysqli_connect($server, $db_user, $db_pwd, $db_name) //connect to the database server
 or die("Could not connect to mysql because " . mysqli_error());

mysqli_select_db($con, $db_name) //select the database
 or die("Could not select to mysql because " . mysqli_error());

if (isset($_GET['user'])) {
    $user = mysqli_real_escape_string($con, $_GET["user"]);
	$user=htmlspecialchars($user,ENT_COMPAT);
} else {
    die('Error');
}

//check if user exist already
$query = "select * from " . $table_name . " where username='$user'";
$result = mysqli_query($con, $query) or die('error');
if (mysqli_num_rows($result)) //if exist then check for activation status
{

    $query = "select activ_key,email from " . $table_name . " where username='$user' and activ_status in (1,2)";
    $result = mysqli_query($con, $query) or die('error');
    if (mysqli_num_rows($result)) {
        echo "Account already activated";
    } else {
        //resend mail
        $query = "select activ_key,email from " . $table_name . " where username='$user' and activ_status in (0,1,2)";
        $result = mysqli_query($con, $query) or die('error');

        $db_field = mysqli_fetch_assoc($result);
        $activ_key = $db_field['activ_key'];
        $email = $db_field['email'];

        //send email for the user with password

        $to = $email;
        $subject = "Activate Account";
        $body = "Hi " . $user . "<br /><br />" .
            "Click the below link to activate your account<br />" .
            "<a href=\"$url/activate.php/?k=$activ_key\"> Activate Account </a>" .
            "<br /><br /><br/ > Link not working? Paste the below into your browser:<br />" .
            $url . "/activate.php/?k=" . $activ_key . "\  <br /><br />Thanks <br />";
        $headers = "From:" . $from_address;
        $headers .= 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        mail($to, $subject, $body, $headers);
        echo "Activation code has been successfully sent to your Email Address";

        //echo "User Account not yet activated.Check your mail for activation details.";
    }

} else {
    die("Username Doesn't exist");
}

?>
</div>
</div>

</body>

</html>
