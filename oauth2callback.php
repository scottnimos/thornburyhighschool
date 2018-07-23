<?php
/*
* This file s no loger required. it uses google oath2 to authenticate users
*/

require_once __DIR__.'/vendor/autoload.php';

session_start();
define('SCOPES', implode(' ', array(
  Google_Service_Sheets::SPREADSHEETS_READONLY)
));
$client = new Google_Client();
$client->setAuthConfigFile('client_secret.json');
$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php');
//$client->setRedirectUri('postmessage');
$client->setScopes(SCOPES);

if (! isset($_GET['code'])) {
  $auth_url = $client->createAuthUrl();
  header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
} else {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/index.php';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}
