<?php
@ob_clean();
if( isset($_GET['src']) ){
	$src = str_replace('&amp;','&', $_GET['src']);
	if( 'http://' == substr($src, 0, 7 ) )header("location:{$src}");
	$request = "http://".$_SERVER ['HTTP_HOST'] .'/' .$src;
}else{
	$request = "http://".$_SERVER ['HTTP_HOST'];
}

$content = file_get_contents($request);
header("Content-type: text/vnd.wap.wml");
echo '<?xml version="1.0" encoding="gbk"?><!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN"	"http://www.wapforum.org/DTD/wml_1.1.xml"><wml>';
preg_match_all( "/\<title\>(.*?)\<\/title\>/i", $content, $title );
echo "<card id=\"wap\" title=\"{$title[1][0]}\"><p>";
$patterns = array(
	'/<br\s*\/?\/>/i',
	'/<\/?(blockquote|div|td|p)+\/?>/i',
	'/<(script.*?)>(.*?)<(\/script.*?)>/si',
	'/<(style.*?)>(.*?)<(\/style.*?)>/si',
	'/<(head.*?)>(.*?)<(\/head.*?)>/si',
	'/<a(.*?)href=[\'\"]+(.*?)[\'\"]+(.*?)>(.*?)<\/a>/ie',
	'/<img(.*?)src=[\'\"]+(.+\.(jpg|gif|png))[\'\"]+(.*?)>/ie',
	'/\?\;/i',
	'/\?/i',
);
$replacements = array(
	"\n",
	"\n",
	'',
	'',
	'',
	"'<a href=\"/wap.php?src='.urlencode('\\2').'\">'.stripslashes('\\4').'</a>'",
	"'<img src=\"\\2\" />'",
	" ",
	" ",
);
$content = preg_replace($patterns, $replacements, $content);
$content = strip_tags($content,"<img><a>");
$content = html_entity_decode($content, ENT_QUOTES, 'UTF-8');
$content = preg_replace("/\&\#.*?\;/i", "", $content);
$content = str_replace(array('$',"\r\n"), array('$$', "\n"), $content);
$content = explode("\n", $content);
for ($i = 0; $i < count($content); $i++) {
	$content[$i] = trim($content[$i]);
	if (str_replace("　", "", $content[$i]) == "") $content[$i] = "";
}
$content = str_replace("<p><br/></p>\n", "", "<p>".implode("<br/></p>\n<p>", $content)."<br/></p>\n");
echo str_replace(array('<p>','</p>'),'',$content);
echo '</p></card></wml>';