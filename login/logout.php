<?php

session_start();

if (isset($_SESSION['login'])) {

    unset($_SESSION['login']);

}

if (isset($_SESSION['admin'])) {

    unset($_SESSION['admin']);

}

//clear twitter access tokens
if (isset($_SESSION['access_token'])) {

    unset($_SESSION['access_token']);

}

if (isset($_SESSION['access_token']['oauth_token_secret'])) {

    unset($_SESSION['access_token']['oauth_token_secret']);

}

if (isset($_SESSION['access_token']['oauth_token'])) {

    unset($_SESSION['access_token']['oauth_token']);

}

if ($_SESSION['token']) {

    unset($_SESSION['token']);

}

session_start();
session_destroy();
header('Location: index.php');
