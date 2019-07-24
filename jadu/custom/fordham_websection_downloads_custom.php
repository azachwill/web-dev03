<?php
if ($_GET["downloadID"] == "3586")
{
?>

 <br /><button class="fu_copytocbbtn" data-clipboard-text="<?php print 'https://'. DOMAIN . '/download/downloads/id/' . $fileItem->id . '/' .$filename; ?>">Copy PDF URL</button>

<?php
}
?>

