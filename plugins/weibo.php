<?php
/*weibo plugin*/
function weibo_get($check,$name,$tbn,$post_type,$tbs,$cookie){
$api=file_get_contents('https://weibo.cn/'.$name);
preg_match_all('/<title>(.+?)的微博<\/title>/',$api,$kd1);//$kd1[1][0]
preg_match_all('/<div class=\"c\" id=\"(.+?)\">(.+?)<span class=\"ct\">(.+?)<\/span><\/div>/',$api,$kd2);
if(preg_match('/<span class=\"kt\">置顶<\/span>/',$kd2[0][0])){
    $text = $kd2[0][1];
    $tweetid=$kd2[1][1];
}else{
    $text = $kd2[0][0];
    $tweetid=$kd2[1][0];
}
if($tweetid!=$check){return array("text"=>$text,"tbpic"=>"","tweetid"=>$tweetid);}else{return '';}
}