<?php
/**
 * @file
 * Take the user when they return from Twitter. Get access tokens.
 * Verify credentials and redirect to based on response from Twitter.
 */

/* Start session and load lib */
session_start();
require_once 'social/twitteroauth/twitteroauth.php';
include 'db.php';

//connect to out users_social database
$con = mysqli_connect($server, $db_user, $db_pwd, $db_name) //connect to the database server
 or die("Could not connect to mysql because " . mysqli_error());

mysqli_select_db($con, $db_name) //select the database
 or die("Could not select to mysql because " . mysqli_error());

/* If the oauth_token is old redirect to the connect page. */
if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
    $_SESSION['oauth_status'] = 'oldtoken';
    header('Location: twitter_clearsessions.php');
}

/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

/* Request access tokens from twitter */
$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

/* Save the access tokens. Normally these would be saved in a database for future use. */
$_SESSION['access_token'] = $access_token;

/* Remove no longer needed request tokens */
unset($_SESSION['oauth_token']);
unset($_SESSION['oauth_token_secret']);

/* If HTTP response is 200 continue otherwise send to connect page to retry */
if (200 == $connection->http_code) {
    /* The user has been verified and the access tokens can be saved for future use */
    $_SESSION['status'] = 'verified';

//twitter login successfull
    //post status to the user if needed
    //$connection->post('statuses/update', array('status' => date(DATE_RFC822)));

    //get username
    $content = $connection->get('account/verify_credentials');
    $user = json_encode($content);
    $user = json_decode($user, true);
    $username = $user["name"];

    //insert user into table
    $query = "select * from " . $table_name_social . " where username='$username' and source='Twitter'";
    $result = mysqli_query($con, $query) or die('error');
    if (mysqli_num_rows($result)) {
        //do nothing
    } else {
        $query = "insert into " . $table_name_social . "(username,email,source) values ('$username','null','Twitter')";

        if (!mysqli_query($con, $query)) {
            die('Error: ' . mysqli_error());

        }
    }

    $_SESSION['username'] = $username;
    header('Location: ./members.php');
} else {
    /* Save HTTP status for error dialog on connnect page.*/
    echo 'Error during authentication <a href=' . $url . '>Click to Try again</a>';
    //header('Location: ./twitter_clearsessions.php');
}
