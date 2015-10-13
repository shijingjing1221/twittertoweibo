<?php
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');

/* If access tokens are not available redirect to connect page. */
// if (empty($_SESSION['access_token']) || empty($_SESSION['acce
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

function getRedirectURL($url){
  $curl = curl_init();
  curl_setopt($curl,CURLOPT_URL,$url);
  curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
  curl_setopt($curl,CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64; rv:24.0) Gecko/201000101 Firefox/24.0');
  //curl_setopt($curl,CURLOPT_FOLLOWLOCATION,true);
  //curl_setopt($curl,CURLOPT_MAXREDIRS,10);
  $rs = curl_exec($curl);
  preg_match_all("/http[^\"\)]*/",$rs,$matches);
  $retURL = $matches[0][1];
  echo "<br/> redirectedURL:  ".$retURL;
  return $retURL;
}
function getRedirectText($text){
  $arr = explode(" ",$text);
  $index = 0;
  foreach($arr as $value){
    echo "<br/>arrValue: ".$value;
    if(stripos($value,"http")===0){
      echo "  ______equial http";
      $value = getRedirectURL($value);
    }
    $reArr[$index] = $value;
    $index++;
  }
  $reText = implode(" ",$reArr);
  echo "<br/>\nreDirectedText:  ".$reText."<br/>\n";
  return $reText;
}
function initWBUrl($arg1,$arg2){
  $wbURL="https://api.weibo.com/2/statuses/update.json";
  $wbURL .= "?access_token=";
  $wbURL .= $arg1;
  $wbURL .= "&status=";
  $wbURL .= $arg2;
  // echo "<br/>".$wbURL."<br/>";
  return $wbURL;
}

function weibo_postAgain($arg1,$arg2){
  echo "arg1:".$arg1."----arg2:".$arg2."<br/>";
  $handle = curl_init(initWBUrl($arg1,urlencode($arg2)));
  echo(initWBUrl($arg1,urlencode($arg2)));
  curl_setopt($handle,CURLOPT_POST,true);
  curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
  $ret = curl_exec($handle);
  print_r($ret);
  curl_close($handle);
}
function weibo_post($arg1,$arg2){
  echo "arg1:".$arg1."----arg2:".$arg2."<br/>";
  $handle = curl_init(initWBUrl($arg1,urlencode($arg2)));
  echo(initWBUrl($arg1,urlencode($arg2)));
  curl_setopt($handle,CURLOPT_POST,true);
  curl_setopt($handle,CURLOPT_RETURNTRANSFER,1);
  $ret = curl_exec($handle);
  print_r($ret);
  curl_close($handle);
  
  //with illegal website, so rePost
  sleep(2);
  $retObj = json_decode($ret);
  if($retObj->{'error_code'}==20018){
    echo "<br/>\n error invalid net address <br/>\n";
    $arg2 = preg_replace("/http:\S*/","",$arg2);
    weibo_postAgain($arg1,$arg2);
  }else{
    echo "<br/>\n error not illegal website <br/>\n";
  }
}
//weibo_post($wbToken,$wbText);


function twitteroauth_row($method, $response, $http_code, $parameters = '') {
   echo "<br/>****************************************<br/>\r\n";
   $response = array_reverse($response);
   foreach($response as $key=>$value){
     print_r($key);
     echo "=>";

     $fp = fopen("/var/www/html/tw/param_test.txt","r");
     $lastid = fgets($fp);
     fclose($fp);
     
     $thisid = $value->{'id_str'};
     echo "thisid:".$thisid."   lastid:".$lastid."<br/>\n";
     if(strcasecmp($thisid,$lastid)<=0){
       echo "same<br/>\n";
       echo "<br/>***************************<br/>\r\n";
       continue;
     }

     $fp = fopen("/var/www/html/tw/param_test.txt","w");
     if(fwrite($fp,$thisid)===false){
        echo "write file failed<br/>\n";
     }
     fclose($fp);

     echo "<br/>\n";


     $text = $value->{'text'};
     $post = preg_replace("/#\S*/","$0#",$text);
     echo $post."_____with link<br/>\n";
     $post = getRedirectText($post);
     echo $post."_____with redirectlink<br/>\n";
     

     $token = "2.00JLONVFuwl1VD431c1282c1lSw4xC";

     weibo_post($token,$post);
     sleep(2);
     echo "<br/>*********************<br/>";
   }
}

function twitteroauth_header($header) {
  echo '<tr><th colspan="4" style="background: grey;">', $header, '</th></tr>';
}

  $method = "statuses/user_timeline";
  $parameters = array('screen_name' => "Michael Saylor","count"=>"10");
  twitteroauth_row($method, $connection->get($method, $parameters), $connection->http_code);

?>
