<?php
/**
* KD·boT 
* Copyright (c) 2015~2018 NULLMIX&KD·Lab All Rights Reserved. 
**/
set_time_limit(0);
ignore_user_abort(true);
require dirname(__FILE__) . '/init.php';
/*check login and get tbs */
$gettbs = json_decode(scurl('http://tieba.baidu.com/dc/common/tbs', '', '', $cookie, '', 1), 1);
if ($gettbs["is_login"] != 1) {
    die("未登录，请执行 php mixbox.php update bduss YOUR_BDUSS\n");
}
$tbs = $gettbs["tbs"];
/*get tasks*/
$tasks = json_decode(file_get_contents(SYSTEM_ROOT . '/db/tasks.json'), 1);
/*get replacelist*/
$replace = json_decode(file_get_contents(SYSTEM_ROOT . '/db/replacelist.json'), 1);
while ($x = 1) {
    foreach ($tasks as $task) {
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
            //echo $task["name"] . '：发送中' . "\n";
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
        }
    }
    echo "system：执行完成，暂停20s\n";
    sleep(20);
}
