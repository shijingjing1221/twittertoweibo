<?php
/**
 * @file
 * 
 */

/* Load required lib files. */
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');

/* If access tokens are not available redirect to connect page. */
// if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
//     header('Location: ./clearsessions.php');
// }
// /* Get user access tokens out of the session. */
// $access_token = $_SESSION['access_token'];
echo "hello";
$access_token = array(
'oauth_token' => "PsctPYw45bbvQ0cuxdVlQ",
'oauth_token_secret' => "BpjgxqSan7tJ7naGzP11hT7nSmaaCMgPfYI3kyVxA",
);
/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

/* If method is set change API call made. Test is called by default. */
$content = $connection->get('account/rate_limit_status');
echo "Current API hits remaining: {$content->remaining_hits}.";

/* Get logged in user to help with tests. */
// $user = $connection->get('account/verify_credentials');
$user = "michael_saylor";

$active = TRUE;
// if (empty($active) || empty($_GET['confirmed']) || $_GET['confirmed'] !== 'TRUE') {
//   echo '<h1>Warning! This page will make many requests to Twitter.</h1>';
//   echo '<h3>Performing these test might max out your rate limit.</h3>';
//   echo '<h3>Statuses/DMs will be created and deleted. Accounts will be un/followed.</h3>';
//   echo '<h3>Profile information/design will be changed.</h3>';
//   echo '<h2>USE A DEV ACCOUNT!</h2>';
//   echo '<h4>Before use you must set $active = TRUE in test.php</h4>';
//   echo '<a href="./test.php?confirmed=TRUE">Continue</a> or <a href="./index.php">go back</a>.';
//   exit;
// }

function twitteroauth_row($method, $response, $http_code, $parameters = '') {
  echo '<tr>';
  echo "<td><b>{$method}</b></td>";
  switch ($http_code) {
    case '200':
    case '304':
      $color = 'green';
      break;
    case '400':
    case '401':
    case '403':
    case '404':
    case '406':
      $color = 'red';
      break;
    case '500':
    case '502':
    case '503':
      $color = 'orange';
      break;
    default:
      $color = 'grey';
  }
  echo "<td style='background: {$color};'>{$http_code}</td>";
  if (!is_string($response)) {
    $response = print_r($response, TRUE);
  }
  if (!is_string($parameters)) {
    $parameters = print_r($parameters, TRUE);
  }
  echo '<td>', strlen($response), '</td>';
  echo '<td>', $parameters, '</td>';
  echo '</tr><tr>';
  echo '<td colspan="4">', substr($response, 0, 400), '...</td>';
  echo '</tr>';

}

function twitteroauth_header($header) {
  echo '<tr><th colspan="4" style="background: grey;">', $header, '</th></tr>';
}

/* Start table. */
echo '<br><br>';
echo '<table border="1" cellpadding="2" cellspacing="0">';
echo '<tr>';
echo '<th>API Method</th>';
echo '<th>HTTP Code</th>';
echo '<th>Response Length</th>';
echo '<th>Parameters</th>';
echo '</tr><tr>';
echo '<th colspan="4">Response Snippet</th>';
echo '</tr>';




/**
 * Timeline Methods.
 */
twitteroauth_header('Timeline Methods');


/* statuses/public_timeline */
twitteroauth_row('statuses/home_timeline', $connection->get('statuses/home_timeline'), $connection->http_code);



/* statuses/user_timeline */
twitteroauth_row('statuses/user_timeline', $connection->get('statuses/user_timeline'), $connection->http_code);




/**
 * List Methods.
 */
twitteroauth_header('List Methods');

/* POST lists */
$method = "{$user->screen_name}/lists";
$parameters = array('name' => 'Twitter OAuth');
$list = $connection->post($method, $parameters);
twitteroauth_row($method, $list, $connection->http_code, $parameters);









/**
 * OAuth Methods.
 */
twitteroauth_header('OAuth Methods');

/* oauth/request_token */
$oauth = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
twitteroauth_row('oauth/reqeust_token', $oauth->getRequestToken(), $oauth->http_code);
