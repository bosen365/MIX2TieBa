<?php
/** 
* KD·boT 
* Copyright (c) 2015~2018 KD·Lab All Rights Reserved. 
**/
set_time_limit(300);
ignore_user_abort(true);
require dirname(__FILE__) . '/init.php';
/*get status*/
$status = json_decode(file_get_contents(SYSTEM_ROOT . '/db/status.json'), 1);
if ($status["status"] == "run" && $status["timestamp"] > time() - 300) {
    die("smartlock：系统正在运行\n");
}
file_put_contents(SYSTEM_ROOT . '/db/status.json', json_encode(array("status" => "run", "timestamp" => time())));
echo "smartlock：系统开始运行\n";
/*check login and get tbs */
$gettbs = json_decode(scurl('http://tieba.baidu.com/dc/common/tbs', '', '', $cookie, '', 1), 1);
if ($gettbs["is_login"] != 1) {
    file_put_contents(SYSTEM_ROOT . '/db/status.json', json_encode(array("status" => "die", "timestamp" => time())));
    die("未登录，请执行 php mixbox.php update bduss YOUR_BDUSS\n");
}
$tbs = $gettbs["tbs"];
/*get tasks*/
$tasks = json_decode(file_get_contents(SYSTEM_ROOT . '/db/tasks.json'), 1);
/*get selfreplace list*/
$selfreplace = json_decode(file_get_contents(SYSTEM_ROOT . '/db/selfreplacelist.json'), 1);
/*get replacelist*/
$replace = json_decode(file_get_contents(SYSTEM_ROOT . '/db/replacelist.json'), 1);
$replace = array_merge($selfreplace, $replace);
$count = 1;
$starttime = time();
foreach ($tasks as $task) {
    if ($count % 99 == 0 && time()<=$starttime+60) {
        $sleeptime=($starttime+60-time());
        echo "system:单分钟请求超限，暂停{$sleeptime}s\n";
        sleep($sleeptime);
        $starttime=time();
    }
    if (!file_exists(SYSTEM_ROOT . '/db/postlock/' . $task["name"] . '_' . $task["tbn"] . '.kd')) {
        $check = 0;
    } else {
        $check = file_get_contents(SYSTEM_ROOT . '/db/postlock/' . $task["name"] . '_' . $task["tbn"] . '.kd');
    }
    //echo $task["name"].'：获取中'."\n";
    $autoget = '';
    switch ($task["get_type"]) {
        case "twitter":
            $autoget = twitter_get($check, $task["name"], $task["tbn"], $task["post_type"], $tbs, $cookie);
            break;
        case "weibo":
            $autoget = weibo_get($check, $task["wbname"], $task["tbn"], $task["post_type"], $tbs, $cookie);
            break;
    }
    if ($autoget == '') {
        echo $task["name"] . '：暂无更新' . "\n";
    } else {
        echo $task["name"] . '：发送中' . "\n";
        $text = strip_tags($autoget["text"]);
        foreach ($replace as $newkd2) {
            $text = str_ireplace($newkd2[0], $newkd2[1], $text);
        }
        $text = chop($text);
        $tbpic = $autoget["tbpic"];
        $tweetid = $autoget["tweetid"];
        file_put_contents(SYSTEM_ROOT . '/db/postlock/' . $task["name"] . '_' . $task["tbn"] . '.kd', $tweetid);
        /*好戏开始*/
        $a = bdtb($task, $text, $tbpic, $tbs, $cookie);
        csv(SYSTEM_ROOT . '/db/log.csv', "a", $a);
        sleep(2);//2s cd防封
    }
    $count++;
    
}
file_put_contents(SYSTEM_ROOT . '/db/status.json', json_encode(array("status" => "die", "timestamp" => time())));
echo "smartlock：完成\n";
