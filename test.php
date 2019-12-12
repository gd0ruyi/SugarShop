<?php 
set_time_limit(0);
$max = 100000/4;
$html = "<pre> ";
for ($i = 0; $i < $max; $i++) {
	$html .= "<font color='#FF0000'>0123456789</font>(" . $i . ")<br />";
}
$html .= "</pre> ";
$html = "content_length=" . mb_strlen($html) . "<br />" . $html;
header("Content-Length: " . mb_strlen($html));
ob_start();
$step = 1000;
for ($i = 0; $i < ceil(mb_strlen($html) / $step); $i++) {
	echo substr($html, ($i * $step), $step);
	ob_flush();
	flush();
}
ob_end_flush();
//echo $html;
 