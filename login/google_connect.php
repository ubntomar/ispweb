<?php
require_once 'social/google-api-php-client/src/Google_Client.php';
require_once 'social/google-api-php-client/src/contrib/Google_PlusService.php';
require_once 'social/google-api-php-client/src/contrib/Google_Oauth2Service.php';
//include('social/google-api-php-client/config.php');
include 'db.php';
//connect to  database
$con = mysqli_connect($server, $db_user, $db_pwd, $db_name) //connect to the database server
 or die("Could not connect to mysql because " . mysqli_error());

mysqli_select_db($con, $db_name) //select the database
 or die("Could not select to mysql because " . mysqli_error());

// Set your cached access token. Remember to replace $_SESSION with a
// real database or memcached.
session_start();

$client = new Google_Client();
$client->setApplicationName('Login With Google+');
// Visit https://code.google.com/apis/console?api=plus to generate your
// client id, client secret, and to register your redirect uri.
$client->setClientId($Clientid);
$client->setClientSecret($Client_secret);
$client->setRedirectUri($Redirect_URIs);
$client->setDeveloperKey($apikeys);
$client->setScopes(array('https://www.googleapis.com/auth/userinfo.email',
    'https://www.googleapis.com/auth/plus.me'));
$client->setApprovalPrompt('auto');
$plus = new Google_PlusService($client);

if (isset($_GET['error'])) {

    echo 'Error during authentication <a href=' . $url . '>Click to Try again</a>';
    exit;
}

if (isset($_GET['code'])) {
    $client->authenticate();
    $_SESSION['token'] = $client->getAccessToken();
    $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
    header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['token'])) {
    $client->setAccessToken($_SESSION['token']);
}
if (isset($_REQUEST['logout'])) {
    unset($_SESSION['token']);
    $client->revokeToken();
}

if ($client->getAccessToken()) {

    $oauth2Service = new Google_Oauth2Service($client);

    // We're not done yet. Remember to update the cached access token.
    // Remember to replace $_SESSION with a real database or memcached.
    $_SESSION['token'] = $client->getAccessToken();
    $userinfo = $oauth2Service->userinfo->get();
    $email = $userinfo['email'];
    // echo $userinfo['email'];
    $me = $plus->people->get('me');
    //print ' <pre>' . $me['name']['givenName'] . '</pre>';
    $username = $me['name']['givenName'];
//check if user exist already
    $query = "select * from " . $table_name_social . " where username='$username' and source='Google'";
    $result = mysqli_query($con, $query) or die('error');
    if (mysqli_num_rows($result)) {
        //do nothing
    } else {
        $query = "insert into " . $table_name_social . "(username,email,source) values ('$username','$email','Google')";

        if (!mysqli_query($con, $query)) {
            die('Error: ' . mysqli_error());

        }

    }

    //redirect to members page
    $_SESSION['username'] = $username;
    header('location:members.php');
} else {
    $authUrl = $client->createAuthUrl();
    // print "<a href='$authUrl'>Connect Me!</a>";
}?>
<?php
  if(isset($authUrl)) {
   // print "<a class='login' href='$authUrl'>Connect Me!</a>";
   header('location:'.$authUrl);
  } else {
   print "<a class='logout' href='../logout.php'>Logout</a>";
  }
?>
