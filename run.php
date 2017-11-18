<?php
/**
 * KD·boT
 * Copyright (c) 2015~2017 NULLMIX&KDCloud All Rights Reserved.
 * 
 */
set_time_limit(0);
ignore_user_abort(true);
require dirname(__FILE__) . '/init.php';
/*check login and get tbs */
$gettbs = json_decode(scurl('http://tieba.baidu.com/dc/common/tbs','','',$cookie,'',1),1);
if ($gettbs["is_login"] != 1) {
    die("未登录");
} else {
    $tbs = $GLOBALS["gettbs"]["tbs"];
}
/*get tasks*/
$tasks = json_decode(file_get_contents(SYSTEM_ROOT . '/db/tasks.json'), 1);
/*get dellist*/
$del = json_decode(file_get_contents(SYSTEM_ROOT . '/db/dellist.json'), 1);

for ($x = 0;$x <= count($tasks) - 1;$x++) {
$api=file_get_contents('https://mobile.twitter.com/'.$tasks[$GLOBALS["x"]]["name"]);
preg_match_all('/<div[^>]*?>(.[\s\S]*?)<\/div>/u',$api,$kd1);
preg_match_all('/<div class="tweet-text" data-id="(.+?)">/u',$api,$kd2);
preg_match('/<strong class=\"fullname\">(.+?)<\/strong>/',$api,$kd3);
$text=chop(str_replace($del," * ",str_replace("@","@ ",strip_tags($kd1[1][8]))));
$tweetid=$kd2[1][0];
$check=file(dirname(__FILE__).'/db/'.$tasks[$x]["name"].'.kd')[0];
if ($tweetid <= $check){
        echo '暂无更新';
echo $tweetid.$check;
    } else {
        
        $text = chop(str_replace($del, " * ", str_replace("@", "@ ", strip_tags($text))));
$fid=json_decode(file_get_contents('http://tieba.baidu.com/f/commit/share/fnameShareApi?ie=utf-8&fname='.$tasks[$GLOBALS["x"]]["tbn"]),1)["data"]["fid"];
        $data = 'co=' . $tasks[$GLOBALS["x"]]["name"] .'：'. $text . '&_t=' . time() . '&tag=11&upload_img_info=&fid=' . $fid . '&src=1&word=' . $tasks[$GLOBALS["x"]]["tbn"] . '&tbs=' . $tbs . '&z=' . $tasks[$GLOBALS["x"]]["tid"];
echo $data;
        $a = json_decode(scurl('https://tieba.baidu.com/mo/q/apubpost',1,$data,$cookie,'http://tieba.baidu.com/p/' . $tasks[$GLOBALS["x"]]["tid"] . '?pn=0&',1),1);
scurl('https://tieba.baidu.com/mo/q/m?kz='. $tasks[$GLOBALS["x"]]["tid"].'&last=1&has_url_param=0&is_ajax=1&post_type=normal&_t=1509825073800',0,'',$cookie,'http://tieba.baidu.com/p/' . $tasks[$GLOBALS["x"]]["tid"] . '?pn=0&',1);
        $log = fopen(SYSTEM_ROOT . '/db/log.csv', "a");
        flock($log, LOCK_EX);
        fwrite($log, '[' . time() . ',' . json_encode($a) . ',"' . $tasks[$x]["name"] . '","' . $text . '"],' . "\r\n");
        fclose($log, LOCK_UN);
        if ($a['no'] != "0") {
            echo $tasks[$x]["name"] . "错误代码#" . $a['no'] . ',错误原因：' . $a['error'] . "\n";
        } else {
            echo $tasks[$x]["name"] . '发送成功' . "\n";
        }
        $fp = fopen(SYSTEM_ROOT . '/db/' . $tasks[$x]["name"] . '.kd', "w");
        flock($fp, LOCK_EX);
        fwrite($fp, $tweetid);
        fclose($fp, LOCK_UN);
    }
}
