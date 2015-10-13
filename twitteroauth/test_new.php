<?php
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');
$access_token = array(
'oauth_token' => "PsctPYw45bbvQ0cuxdVlQ",
'oauth_token_secret' => "BpjgxqSan7tJ7naGzP11hT7nSmaaCMgPfYI3kyVxA",
);
    function getConnectionWithAccessToken($oauth_token, $oauth_token_secret) {

      $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret);

      return $connection;

    }

     

    $connection = getConnectionWithAccessToken($oauth_token["oauth_token"], $oauth_token["oauth_token_secret"]);

    $content = $connection->get("statuses/home_timeline");