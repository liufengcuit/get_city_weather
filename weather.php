<?php
header("Content-type: text/html; charset=utf-8");

function getWeather(){

	$ip_info=GetIpLookup();
	$city = $ip_info['city'];

	$data = file_get_contents("https://free-api.heweather.com/v5/weather?city=".$city."&key=952009dddd28493681e7484183a64881");

	if($data == ""){
	    $data = file_get_contents("https://free-api.heweather.com/v5/weather?city=".$city."&key=c7467223bb3f478cb883881bbd133a1d");
	}

	$arr = [];
	foreach (json_decode($data) as $value) {
		# code...
		array_push($arr, $city);
		array_push($arr, $value[0]->daily_forecast[0]->tmp->min);
		array_push($arr, $value[0]->daily_forecast[0]->tmp->max);
	}
	return $arr;
}
//通过IP地址获取城市及天气情况http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=
function GetIpLookup($ip = ''){  
    if(empty($ip)){  
        $ip = ip();
    }
    $res = file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
    if(empty($res)){
    	return false;
    }
    $jsonMatches = array();
    preg_match('#\{.+?\}#', $res, $jsonMatches);  
    if(!isset($jsonMatches[0])){
    	return false;
    }
    $json = json_decode($jsonMatches[0], true);  
    if(isset($json['ret']) && $json['ret'] == 1){  
        $json['ip'] = $ip;  
        unset($json['ret']);  
    }else{  
        return false;  
    }
    return $json;  
}
//获取IP地址
function ip() {
    //strcasecmp 比较两个字符，不区分大小写。返回0，>0，<0。
    if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $ip = getenv('HTTP_CLIENT_IP');
    } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $ip = getenv('REMOTE_ADDR');
    } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    $res =  preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : '';
    return $res;
    //dump(phpinfo());//所有PHP配置信息
}
?>