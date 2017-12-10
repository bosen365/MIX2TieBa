<?php
/**
 * KD·boT
 * Copyright (c) 2015~2017 NULLMIX&KD·Lab All Rights Reserved.
 * 
 */
set_time_limit(0);
ignore_user_abort(true);
require dirname(__FILE__) . '/init.php';
/*check login and get tbs */
$gettbs = json_decode(scurl('http://tieba.baidu.com/dc/common/tbs','','',$cookie,'',1),1);
if ($gettbs["is_login"] != 1) {
    die("未登录，请执行 php mixbox.php update bduss YOUR_BDUSS\n");
} else {
    $tbs = $GLOBALS["gettbs"]["tbs"];
}
/*get tasks*/
$tasks = json_decode(file_get_contents(SYSTEM_ROOT . '/db/tasks.json'), 1);
/*get dellist*/
$del = json_decode(file_get_contents(SYSTEM_ROOT . '/db/dellist.json'), 1);

foreach ($tasks as $task) {
if(!file_exists(SYSTEM_ROOT . '/db/'.$task["name"].'_'.$task["tbn"].'.kd')){$check=0;}else{
$check=file_get_contents(SYSTEM_ROOT . '/db/'.$task["name"].'_'.$task["tbn"].'.kd');}
$autoapi = json_decode(file_get_contents('https://twitter.com/i/profiles/show/'.$task["name"].'/timeline/tweets?composed_count=0&include_available_features=0&include_entities=0&include_new_items_bar=true&interval=30000&latent_count=0&min_position='.$check),1);
$tweetid=$autoapi["max_position"];
if ($autoapi["max_position"] == ''){
        echo $task["name"].'：暂无更新'."\n";
    } else {
        echo $task["name"].'：正在发送'."\n";
        $api = $autoapi["items_html"];
        preg_match_all('/<p(.+?)>(.+?)<\/p>/s', $api, $kd1);
        //preg_match_all('/data-screen-name=\"(.+?)\" data-name=\"(.+?)\" data-user-id=\"(.+?)\"/', $api, $kd2);
$text='';
$x=1;
foreach ($kd1[2] as $newkd1) {
$text.='('.$x.')'.$newkd1."\n";
$x++;
}
//if(preg_match_all('/pic.twitter.com/',$text)){
//preg_match_all('/https:\/\/pbs.twimg.com\/media\/(.+?).jpg/', $api, $kd3);
//$tbpic=tbpic($tbs,$kd3[0][0],'aqours',"ajax",$cookie);
//echo $tbpic;
//$tbs,$path,$tbn,$mode,$cookie
//}else{$tbpic='';}
$text=chop(str_replace($del," * ",str_replace("@","@ ",strip_tags($text))));
        switch($task["post_type"]){
         case "ajax":
         $text = chop(str_replace($del, " * ", str_replace("@", "@ ", strip_tags($text))));
$fid=json_decode(file_get_contents('http://tieba.baidu.com/f/commit/share/fnameShareApi?ie=utf-8&fname='.$task["tbn"]),1)["data"]["fid"];
        $data = 'co=' . $task["name"] ."：\n". $text . '&_t=' . time() . '&tag=11&upload_img_info=&fid=' . $fid . '&src=1&word=' . $task["tbn"] . '&tbs=' . $tbs . '&z=' . $task["tid"];
        $a = json_decode(scurl('https://tieba.baidu.com/mo/q/apubpost',1,$data,$cookie,'http://tieba.baidu.com/p/' . $task["tid"] . '?pn=0&',1),1);
scurl('https://tieba.baidu.com/mo/q/m?kz='. $task["tid"].'&last=1&has_url_param=0&is_ajax=1&post_type=normal&_t=1509825073800',0,'',$cookie,'http://tieba.baidu.com/p/' . $task["tid"] . '?pn=0&',1);
        fp(SYSTEM_ROOT . '/db/log.csv', "a", json_encode(array(time(),$a,$task["name"],$text)) . ',' . "\r\n");
        if ($a['no'] != "0") {
            echo $task["name"] . "：错误代码#" . $a['no'] . ',错误原因：' . $a['error'] . "\n";
        } else {
            echo $task["name"] . '：发送成功' . "\n";
        }
        fp(SYSTEM_ROOT . '/db/'.$task["name"].'_'.$task["tbn"].'.kd',"w",$tweetid);
       break;
       default:
$text = chop(str_replace($del, " * ", str_replace("@", "@ ", strip_tags($text))));
$t=scurl('http://tieba.baidu.com/mo/m?kz='.$task["tid"],0,'',$cookie,'','kdcloud automatic bot');
preg_match('/<form action=\"(.*?)\" method=\"post\">/', $t , $formurl);	
preg_match('/<input type=\"hidden\" name=\"ti\" value=\"(.*?)\"\/>/', $t, $ti);	
preg_match('/<input type=\"hidden\" name=\"src\" value=\"(.*?)\"\/>/', $t, $src);	
preg_match('/<input type=\"hidden\" name=\"word\" value=\"(.*?)\"\/>/', $t, $word);	
preg_match('/<input type=\"hidden\" name=\"tbs\" value=\"(.*?)\"\/>/', $t, $tbs);	
preg_match('/<input type=\"hidden\" name=\"fid\" value=\"(.*?)\"\/>/', $t, $fid);
preg_match('/<input type=\"hidden\" name=\"floor\" value=\"(.*?)\"\/>/', $t, $floor);
$data='co=' . $task["name"] .'：'. $text . '&ti='.$GLOBALS['to'][1].'&src='.$GLOBALS["src"][1].'&word='.$GLOBALS["word"][1].'&tbs='.$GLOBALS["tbs"][1].'&ifpost=1&ifposta=0&post_info=0&tn=baiduWiseSubmit&fid='.$GLOBALS["fid"][1].'&verify=&verify_2=&pinf=1_2_0&pic_info=&z='.$task["tid"].'&last=0&pn=0&r=0&see_lz=0&no_post_pic=0&floor='.$GLOBALS["floor"][1].'&sub1=%E5%9B%9E%E8%B4%B4';
			
			$a=scurl('http://tieba.baidu.com'.$GLOBALS["formurl"][1],1,$data,$cookie,'http://tieba.baidu.com/mo/m?kz='.$task["tid"],'kdcloud automatic bot');
			if (!preg_match('/<span class=\"light\">回贴成功<\/span>/',$a)){
			    preg_match('/<div class=\"d\">(.+?)<\/div>/',$a,$whyerror);
			   $result=$whyerror[1];
			}else {
			   $result='发送成功';
			}
      echo $task["name"] . '：'.$result."\n";
			fp(SYSTEM_ROOT . '/db/log.csv', "a", json_encode(array(time(),$result,$task["name"],$text)) . ',' . "\r\n");
			file_put_contents(SYSTEM_ROOT . '/db/'.$task["name"].'_'.$task["tbn"].'.kd',$tweetid);
       break;
       }
    }
}
