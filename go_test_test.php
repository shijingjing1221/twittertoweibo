<?php

echo "hello<br/>";

$url = "http://t.co/wr6kCwhHEI";
$url2 = "http://bloom.bg/NoKCLq";
echo $url."<br/>";

 $curl = curl_init();
  curl_setopt($curl,CURLOPT_URL,$url);
  curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
curl_setopt($curl,CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64; rv:24.0) Gecko/201000101 Firefox/24.0');
  //curl_setopt($curl,CURLOPT_FOLLOWLOCATION,true);
  //curl_setopt($curl,CURLOPT_MAXREDIRS,10);
  $rs = curl_exec($curl);
//  echo "<br/>getRedirectURL returnString:  ".$rs;

echo preg_match_all("/http[^\"\)]*/",$rs,$matches);

print_r($matches);
echo "<br/>".$matches[0][1];



?>