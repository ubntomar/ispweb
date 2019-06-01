<?php
session_start();
require_once('db.php');

$con=mysqli_connect($server, $db_user, $db_pwd,$db_name) //connect to the database server
or die ("Could not connect to mysql because ".mysqli_error());

mysqli_select_db($con,$db_name)  //select the database
or die ("Could not select to mysql because ".mysqli_error());


// Include the autoloader provided in the SDK
require_once __DIR__ . '/social/facebook/autoload.php';

// Include required libraries
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

$redirectURL = $url.'/facebook_connect.php'; //Callback URL

$fbPermissions = array('email');  //Optional permissions

// Create our Application instance (replace this with your appId and secret).
$fb = new Facebook(array(
    'app_id' => $fbappid,
    'app_secret' => $fbsecret,
));

// Get redirect login helper
$helper = $fb->getRedirectLoginHelper();

// Try to get access token
try {
    // Already login
    if (isset($_SESSION['facebook_access_token'])) {
        $accessToken = $_SESSION['facebook_access_token'];
    } else {
        $accessToken = $helper->getAccessToken();
    }
 
    if (isset($accessToken)) {
        if (isset($_SESSION['facebook_access_token'])) {
            $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
        } else {
            // Put short-lived access token in session
            $_SESSION['facebook_access_token'] = (string) $accessToken;
 
            // OAuth 2.0 client handler helps to manage access tokens
            $oAuth2Client = $fb->getOAuth2Client();
 
            // Exchanges a short-lived access token for a long-lived one
            $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
            $_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
 
            // Set default access token to be used in script
            $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
        }
 
        // Redirect the user back to the same page if url has "code" parameter in query string
        if (isset($_GET['code'])) {
 
            // Getting user facebook profile info
            try {
                $profileRequest = $fb->get('/me?fields=name,first_name,last_name,email,link,gender,locale,picture');
                $fbUserProfile = $profileRequest->getGraphNode()->asArray();
                // Here you can redirect to your Home Page.
               // echo "<pre/>";
               // print_r($fbUserProfile);
				
				$emailid = $fbUserProfile['email'];
				$name = $fbUserProfile['name'];
				$query = "select * from " . $table_name_social . " where email='$emailid' and source='facebook'";
				$result = mysqli_query($con,$query) or die('error');
				if (mysqli_num_rows($result)) {
				//do nothing
					} else {
						$query = "insert into " . $table_name_social . "(username,email,source) values ('$name','$emailid','facebook')";

						if (!mysqli_query($con,$query)) {
							die('Error: ' . mysqli_error());

						}
					}
				    $_SESSION['fb_access_token'] = $accessToken;
					$_SESSION['username'] = $name;
					header('Location: members.php');
				
				
            } catch (FacebookResponseException $e) {
                echo 'Graph returned an error: ' . $e->getMessage();
                session_destroy();
                // Redirect user back to app login page
                header("Location: ./");
                exit;
            } catch (FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }
        }
    } else {
        // Get login url
 
        $loginURL = $helper->getLoginUrl($redirectURL, $fbPermissions);
		//echo $loginURL;
        header("Location: " . $loginURL);
        
    }
} catch (FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch (FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}
/* 


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
    $result = mysqli_query($con,$query) or die('error');
    if (mysqli_num_rows($result)) {
//do nothing
    } else {
        $query = "insert into " . $table_name_social . "(username,email,source) values ('$name','$emailid','facebook')";

        if (!mysqli_query($con,$query)) {
            die('Error: ' . mysqli_error());

        }
    }

    $_SESSION['fb_access_token'] = $facebook->getAccessToken();
    $_SESSION['username'] = $name;
    header('Location: members.php');
    //header('Location: index.php');
} */


?>
  