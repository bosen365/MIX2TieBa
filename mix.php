<?php 
require dirname(__FILE__) . '/init.php';
if (!SYSTEM_ISCONSOLE) {
    die("本文件仅能在php-cli环境下运行\n");
}
//挖个坑以后再填
switch ($argc) {
    case 2:
        switch ($argv[1]) {
            case "-v":
            case "version":
                //检查版本号
                echo 'MIX2TB ' . SYSTEM_VER . '(' . CHECK_VER . ')' . "\n";
                break;
            case "-l":
            case "list":
                //任务列表
                $tasks = json_decode(file_get_contents(SYSTEM_ROOT . '/db/tasks.json'), 1);
                $x=0;
                foreach ($tasks as $task) {
                    echo "{$x}\n - 用户名 {$task['name']}\n - 贴吧名 {$task['tbn']}\n - 贴id {$task['tid']}\n - 发送方式 {$task['post_type']}\n - 获取类型 {$task['get_type']}\n";
                    $x++;
                }
                break;
            default:
                help();
                break;
        }
        break;
    case 3:
        switch ($argv[1]) {
            case "delete":
            case "del":
                $tasks = json_decode(file_get_contents(SYSTEM_ROOT . '/db/tasks.json'), 1);
                array_splice($tasks,$argv[2],1);
                file_put_contents(SYSTEM_ROOT . '/db/tasks.json', json_encode($tasks));
                echo "已删除第{$argv[2]}项\n";
                break;
            case "update":
                //更新大项
                switch ($argv[2]) {
                    case "dellist":
                        $newdellist = file_get_contents('https://kdwnil.github.io/api/mix2tieba/replacelist.json');
                        file_put_contents(SYSTEM_ROOT . '/db/replacelist.json', $newdellist);
                        echo "已更新替换词列表 \n";
                        break;
                    case "twtotb":
                        break;
                    case "check":
                        $check = json_decode(file_get_contents("https://kdwnil.github.io/api/mix2tieba/version.json"), 1);
                        if ($check["check_ver"] > CHECK_VER) {
                            echo "系统更新 \n 版本号：" . $check["system_ver"] . "（" . $check["check_ver"] . "）\n 更新内容：" . $check["check_data"] . "\n";
                        } else {
                            echo "系统更新 \n 无更新\n";
                        }
                        break;
                    default:
                        help();
                        //你啥都不填怪我咯
                        break;
                }
                break;
        }
        break;
    case 4:
        switch ($argv[1]) {
            case "update":
                //更新大项
                switch ($argv[2]) {
                    case "bduss":
                        file_put_contents(SYSTEM_ROOT . '/db/settings.json', json_encode(array("bduss" => $argv[3], "la" => "zh-CN")));
                        echo "已更新您的BDUSS \n";
                        break;
                    default:
                        help();
                        //你啥都不填怪我咯
                        break;
                }
                break;
            default:
                help();
                break;
        }
}
