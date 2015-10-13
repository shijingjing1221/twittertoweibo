 static $hasError;
   $retArr = json_decode($ret);
  if(array_key_exists("error",$retArr) && $hasError==0){
      $hasError=1;
      if($retArr['error_code']==10024){

	echo "error"."<br/>";
	$arg2 = preg_replace("/http:\S*/","",$arg2);
	//$post = $text;
	echo ($arg2);
	echo "________with link<br/>";
	curl_close($handle);
	weibo_post($arg1,$arg2);
      }
  }else{
    $hasError = 0;
    curl_close($handle);
  }
  echo "<br/>..". $hasError."hasError";
 