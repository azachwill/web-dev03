<?php

        $programurl = "https://bulletin.fordham.edu/programs/programs.xml";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $programurl);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,  true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$programtext = curl_exec($ch);
curl_close($ch);

$prog_url = new SimpleXMLElement($xmlFC);
$prog_url = $programtext->programlinkstext;

$pt = strstr($programtext, '<ul> <li>');
$pt = strstr($pt,'</ul></div>');
//$pt = substr($pt,0,-6);
//$pt = substr($html, 20, -1);

echo $prog_url;
?>



