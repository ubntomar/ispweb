<?php
session_start();
require_once 'db.php';

$con = mysqli_connect($server, $db_user, $db_pwd, $db_name) //connect to the database server
 or die("Could not connect to mysql because " . mysqli_error());

mysqli_select_db($con, $db_name) //select the database
 or die("Could not select to mysql because " . mysqli_error());

require 'social/facebook/facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
    'appId' => $fbappid,
    'secret' => $fbsecret,
));

// Get User ID
$user = $facebook->getUser();

// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($user) {
    try {
        // Proceed knowing you have a logged in user who's authenticated.
        $user_profile = $facebook->api('/me?fields=email,name,first_name,last_name');
    } catch (FacebookApiException $e) {
        error_log($e);
        $user = null;
    }
}

// Login or logout url will be needed depending on current user state.
if ($user) {
    $logoutUrl = $facebook->getLogoutUrl();
} else {
    $loginUrl = $facebook->getLoginUrl(
        array('scope' => 'email'));
}

if (!$user) {
    header('location:' . $loginUrl);
}

if ($user) {

    $emailid = $user_profile['email'];
    $name = $user_profile['first_name'] . $user_profile['last_name'];
    $query = "select * from " . $table_name_social . " where email='$emailid' and source='facebook'";
    $result = mysqli_query($con, $query) or die('error');
    if (mysqli_num_rows($result)) {
//do nothing
    } else {
        $query = "insert into " . $table_name_social . "(username,email,source) values ('$name','$emailid','facebook')";

        if (!mysqli_query($con, $query)) {
            die('Error: ' . mysqli_error());

        }
    }

    $_SESSION['fb_access_token'] = $facebook->getAccessToken();
    $_SESSION['username'] = $name;
    header('Location: members.php');
    //header('Location: index.php');
}
