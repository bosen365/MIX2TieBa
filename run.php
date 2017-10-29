<?php
header("Content-type: text/html; charset=utf-8");
set_time_limit(0);
ignore_user_abort(true);
require dirname(__FILE__) . '/init.php';
/*check login and get tbs */
$gettbs = json_decode(scurl('http://tieba.baidu.com/dc/common/tbs', '', '', $cookie, '', 1), 1);
if ($gettbs["is_login"] != 1) {
    die("未登录");
} else {
    $tbs = gettbs["tbs"];
}
/*get tasks*/
$tasks = json_decode(file_get_contents(SYSTEM_ROOT . '/db/tasks.json'), 1);
/*get dellist*/
$del = json_decode(file_get_contents(SYSTEM_ROOT . '/db/dellist.json'), 1);
for ($x = 0;$x <= count($tasks) - 1;$x++) {
    $autoapi = json_decode(file_get_contents('https://twitter.com/i/profiles/show/' . $tasks[$x]["name"] . '/timeline/tweets?composed_count=0&include_available_features=0&include_entities=0&include_new_items_bar=true&interval=30000&latent_count=0&min_position=' . file(SYSTEM_ROOT . '/db/' . $tasks[$x]["name"] . '.kd') [0]), 1);
    if ($autoapi["max_position"] = 'null') {
        echo '暂无更新';
    } else {
        $api = $autoapi["items_html"];
        preg_match_all('/<p(.+?)>(.+?)<\/p>/s', $api, $kd1);
        preg_match_all('/data-screen-name=\"(.+?)\" data-name=\"(.+?)\" data-user-id=\"(.+?)\"/', $api, $kd2);
        print_r($kd1);
        $text = '';
        for ($y = 0;$y <= count($kd1[2]);$y++) {
            $text.= $kd1[2][$y] . "\n";
        }
        $text = chop(str_replace($del, " * ", str_replace("@", "@ ", strip_tags($text))));
        $data = 'co=' . str_replace($del, " * ", $kd2[0][0]) . '(@' . str_replace($del, ' * ', $tasks[$x]["name"]) . ')：' . "\n" . urlencode(htmlentities($text, ENT_DISALLOWED, 'UTF-8', 0)) . '&_t=' . time() . '&tag=11&upload_img_info=&fid=' . $tasks[$x]["fid"] . '&src=1&word=' . $tasks[$x]["tbn"] . '&tbs=' . $tbs . '&z=' . $tasks[$x]["tid"];
        $a = json_decode(scurl('https://tieba.baidu.com/mo/q/apubpost', 1, $data, $cookie, 'http://tieba.baidu.com/p/' . $tasks[$x]["tid"] . '?pn=0&', 1), 1);
        $log = fopen(SYSTEM_ROOT . '/db/log.csv', "a");
        flock($log, LOCK_EX);
        fwrite($log, '[' . time() . ',' . json_encode($a) . ',"' . $tasks[$x]["name"] . '","' . $text . '"]' . "\r\n");
        fclose($log, LOCK_UN);
        if ($a['no'] != "0") {
            echo $tasks[$x]["name"] . "错误代码#" . $a['no'] . ',错误原因：' . $a['error'] . "\n";
        } else {
            echo $tasks[$x]["name"] . '发送成功' . "\n";
        }
        $fp = fopen(SYSTEM_ROOT . '/db/' . $tasks[$x]["name"] . '.kd', "w");
        flock($fp, LOCK_EX);
        fwrite($fp, $autoapi["max_position"]);
        fclose($fp, LOCK_UN);
    }
}
