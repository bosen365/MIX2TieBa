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
    die("未登录\n");
} else {
    $tbs = $GLOBALS["gettbs"]["tbs"];
}
/*get tasks*/
$tasks = json_decode(file_get_contents(SYSTEM_ROOT . '/db/tasks.json'), 1);
/*get dellist*/
$del = json_decode(file_get_contents(SYSTEM_ROOT . '/db/dellist.json'), 1);

foreach ($tasks as $task) {
$api=file_get_contents('https://mobile.twitter.com/'.$task["name"]);
preg_match_all('/<div[^>]*?>(.[\s\S]*?)<\/div>/u',$api,$kd1);
preg_match_all('/<div class="tweet-text" data-id="(.+?)">/u',$api,$kd2);
preg_match('/<strong class=\"fullname\">(.+?)<\/strong>/',$api,$kd3);
$text=chop(str_replace($del," * ",str_replace("@","@ ",strip_tags($kd1[1][8]))));
$tweetid=$kd2[1][0];
$check=file(dirname(__FILE__).'/db/'.$task["name"].'.kd')[0];
if ($tweetid <= $check){
        echo '暂无更新'."\n";
    } else {
        switch($task["post_type"]){
         case "ajax":
         $text = chop(str_replace($del, " * ", str_replace("@", "@ ", strip_tags($text))));
$fid=json_decode(file_get_contents('http://tieba.baidu.com/f/commit/share/fnameShareApi?ie=utf-8&fname='.$task["tbn"]),1)["data"]["fid"];
        $data = 'co=' . $task["name"] .'：'. $text . '&_t=' . time() . '&tag=11&upload_img_info=&fid=' . $fid . '&src=1&word=' . $task["tbn"] . '&tbs=' . $tbs . '&z=' . $task["tid"];
        $a = json_decode(scurl('https://tieba.baidu.com/mo/q/apubpost',1,$data,$cookie,'http://tieba.baidu.com/p/' . $task["tid"] . '?pn=0&',1),1);
scurl('https://tieba.baidu.com/mo/q/m?kz='. $task["tid"].'&last=1&has_url_param=0&is_ajax=1&post_type=normal&_t=1509825073800',0,'',$cookie,'http://tieba.baidu.com/p/' . $task["tid"] . '?pn=0&',1);
        fp(SYSTEM_ROOT . '/db/log.csv', "a", json_encode(array(time(),$a,$task["name"],$text)) . ',' . "\r\n");
        if ($a['no'] != "0") {
            echo $task["name"] . "：错误代码#" . $a['no'] . ',错误原因：' . $a['error'] . "\n";
        } else {
            echo $task["name"] . '：发送成功' . "\n";
        }
        fp(SYSTEM_ROOT . '/db/' . $task["name"] . '.kd',"w",$tweetid);
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
      echo $result."\n";
			fp(SYSTEM_ROOT . '/db/log.csv', "a", json_encode(array(time(),$result,$task["name"],$text)) . ',' . "\r\n");
			fp(SYSTEM_ROOT . '/db/' . $task["name"] . '.kd',"w",$tweetid);
       break;
       }
    }
}
