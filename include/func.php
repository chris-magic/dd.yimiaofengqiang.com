<?php
function getapiurl($website){
	$apiIp = '121.199.33.15';
	return 'http://'.$apiIp.'/uzcaiji/type/'.$website.'.html';
}

function get_url_content($url) {
	$contents=file_get_contents($url);
	if($contents){
		return $contents;
	}elseif(function_exists("curl_init")){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
		$contents = curl_exec($ch);
		curl_close($ch);
		return $contents;
	}
}
function get_contents($url){
//	$contents=file_get_contents($url);
//	if($contents){
//		return $contents;
//	}elseif(function_exists("curl_init")){
                $proxy = 'http://120.198.243.54:80';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt ($ch, CURLOPT_PROXY, $proxy);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		$contents = curl_exec($ch);
                curl_close($ch);
                return $contents;
//                if(is_array(json_decode(iconv('gbk','utf-8',trim($contents)),1))){
//                    return $contents;
//                }else{
//                    $GLOBALS['G_SP']['randip'] = getRandIp();
//                    get_contents($url);
//                }
//	}
}
//获取文件列表
function list_dir($dir){
	$result = array();
	if (is_dir($dir)){
		$file_dir = scandir($dir);
		foreach($file_dir as $file){
			if ($file == '.' || $file == '..'){
				continue;
			}
			elseif (is_dir($dir.$file)){
				$result = array_merge($result, list_dir($dir.$file.'/'));
			}
			else{
				array_push($result, $dir.$file);
			}
		}
	}
	return $result;
}

 function getshorturl($long_url){
     $apiKey = '3780574640';
     $apiUrl = 'http://api.t.sina.com.cn/short_url/shorten.json?source='.$apiKey.'&url_long='.$long_url;
     $response = file_get_contents($apiUrl);
     $json = object_to_array(json_decode($response));
     return $json[0]['url_short'];
}

function object_to_array($obj)
{
		$_arr = is_object($obj) ? get_object_vars($obj) : $obj;
		foreach ($_arr as $key => $val)
		{
			$val = (is_array($val) || is_object($val)) ? object_to_array($val) : $val;
			$arr[$key] = $val;
		}
		return $arr;
}
?>