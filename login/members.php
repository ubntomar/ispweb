<?php 
session_start();
if ( !isset($_SESSION['login']) || $_SESSION['login'] !== true) {
        if(empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])){
            if ( !isset($_SESSION['token'])) {
                if ( !isset($_SESSION['fb_access_token'])) {
                header('Location: index.php');
                exit;
                }
            }
        }
}

?>



<?php
$urlprev=$_GET['urlprev'];
$role=$_SESSION["role"];
//     header('../tick.php'); /* Redirect browser */
// }else{
//     header('../register-pay.php'); /* Redirect browser */
// }
if($urlprev) 
    $urltext="Location: ../$urlprev";
else {
    if($role=="tecnico") $urltext="Location: ../public/tick.php?role=$role";     
    if($role!="tecnico") $urltext="Location: ../public/registerPay.php?role=$role";       
}
header($urltext); /* Redirect browser */

/* Make sure that code below does not get executed when we redirect. */
exit;
?>


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
        <h2><?php include('db.php'); echo $logotxt; ?></h2>

    </div>
    <form class="form-horizontal" id="login_form">
        <h2><?php echo "hi ".$_SESSION['username']; ?></h2>
        <h2>You are now logged in <?php echo "hi url: $urlprev"; ?></h2>

        <div class="line"></div>

        <?php
if ( isset($_SESSION['login']) || $_SESSION['login'] == true) {
?>
        <a class="forgotten-password-link" href="user_change_password.php">Change password?</a>
        <?php 
}
?>
        <a href="logout.php" class="btn btn-lg btn-primary btn-register">Log Out</a>
        <div class="messagebox">
            <div id="alert-message"></div>
        </div>
    </form>

</body>

</html>