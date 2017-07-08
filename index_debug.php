<?php
//$doc = new DOMDocument();

$txt = file_get_contents("html/table_debug.html_txt");
$stamp = date("Y-m-d");
$txt = str_replace('js_time_stamp', $stamp, $txt);
echo $txt;

//@$doc->loadHTML($txt);
//echo $doc->saveHTML();
?>