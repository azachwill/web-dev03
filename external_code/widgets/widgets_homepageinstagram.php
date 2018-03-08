FRONT-END
----------------------------------------------------------
<div id="fu_instagram">
            <div class="fu_headtext">
                %FU_INSTA_TITLE%
            </div>
            <div class="fu_viewprograms">
                <a href="%FU_INSTA_URL%">VIEW OUR INSTAGRAM PAGE</a></div>
            <div style="clear:both;"></div>

            <?php
            $fu_insta_name = "%FU_INSTA_NAME%";
            $html = '';
            $url = '';
            $fu_namehash = 0;

            $fu_clientid = "%FU_CLIENTID%";
            $fu_accesstoken = "%FU_ACCESSTOKEN%";
            $fu_nodata = 0;
            
/* cache file is created from:
$fu_insta_name will give a unique filename string to every widget that calls json from fu_insta_name;
$fu_clientid will give a unique filename string to every widget that calls json from fu_clientid.
Every widget placement generates a unique cache file based on the type of json call & instagram account username/clientid
*/
            if (strlen($fu_insta_name) > 0) {
                $url = 'https://www.instagram.com/' . $fu_insta_name . '/';
                $fu_namehash = 1;
                $fu_nodata = 0;
               $CACHE_FILE_PATH = '/var/www/jadu/public_html/site/custom_scripts/instagramcache/fu_instagram_' . $fu_insta_name . '.txt';
            } elseif ($fu_clientid && $fu_accesstoken) {
                $url = 'https://api.instagram.com/v1/users/'.$fu_clientid.'/media/recent/?access_token='.$fu_accesstoken;
                $fu_namehash = 0;
                $fu_nodata = 0;
               $CACHE_FILE_PATH = '/var/www/jadu/public_html/site/custom_scripts/instagramcache/fu_instagram_' . $fu_clientid . '.txt';
            } else {
                $url= 'Please  complete the form data';
                $fu_namehash = 0;
                $fu_nodata = 1;
                $CACHE_FILE_PATH = '';
            }
//---------------------------------------
//Cache and cURL: If cache file is > 24 hrs old, cURL for the JSON, else read from the cached file
            $ENABLE_CACHE = true;
            $CACHE_TIME_HOURS = 12;
            
            if ($ENABLE_CACHE && file_exists($CACHE_FILE_PATH) && (time() - filemtime($CACHE_FILE_PATH) < ($CACHE_TIME_HOURS * 60 * 60))) {
                $html = @file_get_contents($CACHE_FILE_PATH);
                // echo "cached Last modified: ".date("F d Y H:i:s.",filemtime($CACHE_FILE_PATH ));
            } else {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                $html = curl_exec($ch);
                curl_close($ch);

                @file_put_contents($CACHE_FILE_PATH, $html);
               //echo "noncached Last modified: ".date("F d Y H:i:s.",filemtime($CACHE_FILE_PATH ));
            echo $CACHE_FILE_PATH;
            }

//-----------------------------------------------
//function to display instagram images;  and if there is any instagram data ($no_data)
//if account name  is used:
            function fu_insta_disp($fu_namehash, $html, $fu_nodata) {
                $imgs = '';
                $data = '';
                $fu_nodata = $fu_nodata;
                
                 $img_temp1 = '';
                 $img_temp2 = '';
                
                if ($fu_namehash == 1 && $fu_nodata == 0) {
                    $isavail = strpos($html, 'Sorry, this page'); //check for valid content
//if content exists, then display
                    
                        $html = strstr($html, 'window._sharedData = ');
                        $html = strstr($html, '</script>', true);
                        //$html = substr($html,0,-6);
                        $html = substr($html, 20, -1);
                        $data = json_decode($html);
                  
                    $thearray=($data->entry_data->ProfilePage[0]->user->media->nodes);

                    $imga = "";
                    $imgb = "";
                    $img_link = "";
                    $fu_count = 0;
                    foreach ($thearray as $obj) {
                        $imga = $obj->thumbnail_src;
                        $img_link = $obj->code;
                        $img_caption = preg_replace('/[^\w\s\.#]/', '', $obj->caption);

                        $img_temp1 = "<a href='https://www.instagram.com/p/".$img_link."'><img src='".$imga."' alt='Fordham University Instagram ".$fu_count1."' title='".$img_caption."' /></a>";
                        $img_temp2 = $img_temp2 . $img_temp1;
                        $fu_count++;
                        if ($fu_count == 5)
                            break;
                    }
                   $img = $img_temp2;  
                 echo $img;
                }
//if client id / accesstoken is used 
               elseif ($fu_namehash == 0 && $fu_nodata == 0) {
                    $data = json_decode($html, true);

                    for ($insta_i = 0; $insta_i <= 4; $insta_i++) {
                        $img_temp1 = "<a href='" . $data['data'][$insta_i]['link'] . "'><img src='" . $data['data'][$insta_i]['images']['thumbnail']['url'] . "' alt='Fordham University Instagrammm " . $insta_i . "' title='" . preg_replace('/[^\w\s\.#]/', '', $data['data'][$insta_i]['caption']['text']) . "'></a>";
                        $img_temp2 = $img_temp2 . $img_temp1;
                
                    }
                    $imgs = $img_temp2;
                    echo $imgs;
 //if no data
                    } else {
                   echo "Please enter Instagram account information";
                }
         //       echo $imgs;
            }

fu_insta_disp($fu_namehash,$html,$fu_nodata);
            ?>


-----------------------------------------------------------------
SETTINGS
-----------------------------------------------------------------
<table class="form_table" id="tbl_widget_content">
<input type="hidden" value="<?php print $DOMAIN; ?>" id="DOMAIN" />

<tr>
<td class="label_cell">Instagram Title: </td>
<td class="data_cell"><input id="fu_insta_title" value="" size="45" type="text" />
</td></tr>

<tr>
<td class="label_cell">Instagram Page URL:</td>
<td class="data_cell"><input id="fu_insta_url" value="" size="45" type="text" />
</td></tr>

<tr>
<td class="label_cell" colspan="2" style="text-align:left;">
What will you use to create the Instagram feed?<br />
<input type="radio" name="fu_instatype" id="fu_instatype_1" value="username" onClick="getUsername()">Instagram Username <br />
<input type="radio" name="fu_instatype" id="fu_instatype_2" value="clientid" onClick="getClientid()">Instagram Client ID/Access Token combination</td><br />
</td></tr>

<tr>
<td class="label_cell" colspan="2">

<div id="instaUsername"  style="text-align:left;">
Instagram User Name: 
<input id="fu_insta_name" value="" size="25" type="text" />
</div>

<div id="instaClientid"  style="text-align:left;">
Instagram Client ID: <input id="fu_clientid" value="" size="25" type="text" /><br />
Access Token: <input id="fu_accesstoken" value="" size="25" type="text" style="margin-left: 2.8em;" />
</div>
</td></tr>
</table>


-----------------------------------------------------------------
SETTINGS JAVASCRIPT
-----------------------------------------------------------------
function getUsername() {
  document.getElementById("instaUsername").style.display = "block";
  document.getElementById("instaUsername").style.visibility = "visible";
   document.getElementById("instaClientid").style.display = "none";
  document.getElementById("instaClientid").style.visibility = "hidden";
}
function getClientid() {
  document.getElementById("instaClientid").style.display = "block";
  document.getElementById("instaClientid").style.visibility = "visible";
   document.getElementById("instaUsername").style.display = "none";
  document.getElementById("instaUsername").style.visibility = "hidden";
}
 document.getElementById("instaUsername").style.display = "none";
  document.getElementById("instaUsername").style.visibility = "hidden";
   document.getElementById("instaClientid").style.display = "none";
  document.getElementById("instaClientid").style.visibility = "hidden";

var currentLinkEdit = -1;
var widgetLinks = new Array();
var oldsave = $('saveWidgetProperty').onclick;

if (typeof $('saveWidgetProperty').onclick != 'function') {
    $('saveWidgetProperty').onclick = commitWidgetLinks;
}
else {
    $('saveWidgetProperty').onclick = function () {
        if (commitWidgetLinks()) {
            oldsave();
        }
    }
}

function commitWidgetLinks ()
{ 
  var fu_accesstoken = $('fu_accesstoken').value; 
    if (fu_accesstoken == '' && document.getElementById('fu_clientid').value != '') {
        alert('Please enter an Access Token');
        return false;
    }
  
    widgetItems[activeWidget].settings = new Object();
  widgetItems[activeWidget].settings['fu_accesstoken'] = fu_accesstoken;
    return true;
}




