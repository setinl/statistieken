<?php
$txt = file_get_contents("html/status.html_txt");
$stamp = date("Y-m-d");
$txt = str_replace('js_time_stamp', $stamp, $txt);
echo $txt;
?>