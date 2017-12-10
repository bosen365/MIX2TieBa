<?php
function  scurl ($url,$wp,$data,$cookie,$referer,$user_agent){
	$ch=curl_init($url);
	switch($user_agent){
	    case 1:
	        curl_setopt($ch,CURLOPT_USERAGENT ,'Mozilla/5.0 (Linux; Android 5.1.1; Nexus 6 Build/LYZ28E) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Mobile Safari/537.36');
	        break;
	    case 2:
	        curl_setopt($ch,CURLOPT_USERAGENT ,'Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1');
	        break;
	    case 3:
	        curl_setopt($ch,CURLOPT_USERAGENT ,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.78 Safari/537.36');
	        break;
	    case 4:
	        curl_setopt($ch,CURLOPT_USERAGENT ,'Mozilla/5.0 (Symbian/3; Series60/5.2 NokiaN8-00/012.002; Profile/MIDP-2.1 Configuration/CLDC-1.1 ) AppleWebKit/533.4 (KHTML, like Gecko) NokiaBrowser/7.3.0 Mobile Safari/533.4 3gpp-gba');
	        break;
	        
	    default:
	        curl_setopt($ch,CURLOPT_USERAGENT ,$user_agent);
	        break;
	}
	curl_setopt($ch,CURLOPT_RETURNTRANSFER ,1);
	if(strlen($cookie)>0){
	    curl_setopt($ch,CURLOPT_COOKIE ,$cookie);
	}
	if(strlen($referer)>0){
	    curl_setopt($ch, CURLOPT_REFERER,$referer);
	}
	if ($wp=1){
		curl_setopt($ch,CURLOPT_POST ,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS ,$data);
	}
	$content=curl_exec($ch);
	curl_close($ch);
	return $content;
}
function newscurl($url,$settings){

}