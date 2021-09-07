#!/usr/bin/php
<?php
//Dedupe /var/www/jadu/public_html/bulletin/programs_removeurls.xml:
$xml = new DOMDocument();
$xml->load('/var/www/jadu/public_html/bulletin/programs_removeurls.xml');

$xsl = new DOMDocument;
$xsl->load('programs.xsl');

$proc = new XSLTProcessor();
$proc->importStyleSheet($xsl);

//create new deduped temp file
$programs_deduped = $proc->transformToXML($xml);


//echo "$programs_deduped" > programs_deduped.txt;

@file_put_contents("/var/www/jadu/public_html/bulletin/programs_deduped.xml", $programs_deduped);


?>
