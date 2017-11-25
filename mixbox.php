<?php
require dirname(__FILE__) . '/init.php';
if(!SYSTEM_ISCONSOLE){die("本文件仅能在php-cli环境下运行\n");}//挖个坑以后再填
switch ($argv[1]) {
    case "version": //检查版本号
        echo SYSTEM_VER . '(' . CHECK_VER . ')' . "\n";
    break;
    case "update": //更新大项
        switch ($argv[2]) {
            case "dellist":
                $newdellist=file_get_contents('https://raw.githubusercontent.com/kdwnil/twtotb/master/db/dellist.json');
                fp(SYSTEM_ROOT . '/db/dellist.json', "w",$newdellist);
                echo "已更新敏感词列表\n";
            break;
            case "twtotb":
            break;
            case "bduss":
                fp(SYSTEM_ROOT . '/settings.php', "w",'<?php' . "\r\n" . '$bduss=\'' . $argv[3] . '\';' . "\r\n" . '$cookie=\'BDUSS=\'.$bduss;');
                 echo "已更新您的BDUSS\n";
            break;
            default:
                help(); //你啥都不填怪我咯
                
        }
    break;
    case "add": //增加一个任务
        
    break;
    case "del": //删除一个任务
        
    break;
    case "check": //检查更新
        
    break;
    case "list": //任务列表
        $tasks = json_decode(file_get_contents(SYSTEM_ROOT . '/db/tasks.json'), 1);
        foreach ($tasks as $task) { echo "用户名：{$task['name']}|贴吧名：{$task['tbn']}|贴id：{$task['tid']}|发送方式：{$task['post_type']}|获取类型：{$task['get_type']}\n"; }
    break;
    default:
        help();
    break;
}
