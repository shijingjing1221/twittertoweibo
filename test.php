<?php
/**
 * @file
 * 
 */

/* Load required lib files. */
echo "hello";
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
'oauth_token' => "2352914474-ApwYXlRHKpqJi7C4AEz59eYRjclTkEkZsHbCUhK",
'oauth_token_secret' => "hlZJTkVDulUgpUVGr8Py2VV2ZbMJOxnJdVMNbLtQPrN8e",
);
/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
echo "a";
//  If method is set change API call made. Test is called by default. 
$content = $connection->get('account/rate_limit_status');
echo "b";
echo "Current API hits remaining: {".$content->remaining_hits."}.";

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


function initWBUrl($arg1,$arg2){
  $wbURL="https://api.weibo.com/2/statuses/update.json";
  $wbURL .= "?access_token=";
  $wbURL .= $arg1;
  $wbURL .= "&status=";
  $wbURL .= $arg2;
  // echo "<br/>".$wbURL."<br/>";
  return $wbURL;
}

function weibo_post($arg1,$arg2){
  echo "arg1:".$arg1."----arg2:".$arg2."<br/>";
  $handle = curl_init(initWBUrl($arg1,$arg2));
  echo(initWBUrl($arg1,$arg2));
  curl_setopt($handle,CURLOPT_POST,true);
  curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
  $ret = curl_exec($handle);
 print_r($ret);
 curl_close($handle);
  
 

}
//weibo_post($wbToken,$wbText);


function twitteroauth_row($method, $response, $http_code, $parameters = '') {
  echo '<tr>';
 
 
  // print_r($response);
   echo "<br/>****************************************<br/>";
   $response = array_reverse($response);
   foreach($response as $key=>$value){
     print_r($key);
     echo "=>";
     // print_r($value);
     echo "<br/>";
     echo "<br/>***************************<br/>";

     $thisid = $value->{'id_str'};
     static $lastid = "436791321211449344";
     echo "thisid:".$thisid."   lastid:".$lastid;
     if(strcasecmp($thisid,$lastid)<=0){
       echo"same";
       continue;
     }
     $lastid = $thisid;
     echo "<br/>";


     $text = $value->{'text'};
     $post = preg_replace("/#\S*/","$0#",$text);
     echo $post."_____with link<br/>";
     

     $token = "2.00uj9JACuwl1VD699af49a280tL7qt";

     //$post = "lsjfoijfewoifjewofj http://www.bloomberg.com/news/2014.html";
     weibo_post($token,urlencode($post));
     // weibo_post($token,$post);
     // echo "<br/>*********************<br/>";
     //echo "<br/>*********************<br/>";
     echo "<br/>*********************<br/>";

     sleep(1);
   }

}

function twitteroauth_header($header) {
  echo '<tr><th colspan="4" style="background: grey;">', $header, '</th></tr>';
}






/* GET lists */
 $time_interval = 600;
 $index = 0;
echo "hello";
ignore_user_abort();
set_time_limit(0);
do{
  $index++;
  if($index>384){
    break;
  }

  $method = "statuses/user_timeline";
  $parameters = array('screen_name' => "michael_saylor","count"=>"50");
  twitteroauth_row($method, $connection->get($method, $parameters), $connection->http_code);

  sleep($time_interval);
}while(true);


?>