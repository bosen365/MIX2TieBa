<?php
require dirname(__FILE__) . '/init.php';
if(!SYSTEM_ISCONSOLE){die("本文件仅能在php-cli环境下运行\n");}//挖个坑以后再填
switch ($argc) {
  case 2:
    switch ($argv[1]){
case "version": //检查版本号
        echo 'MIX2TB '.SYSTEM_VER . '(' . CHECK_VER . ')' . "\n";
    break;
case "list": //任务列表
        $tasks = json_decode(file_get_contents(SYSTEM_ROOT . '/db/tasks.json'), 1);
        foreach ($tasks as $task) { echo "用户名：{$task['name']}|贴吧名：{$task['tbn']}|贴id：{$task['tid']}|发送方式：{$task['post_type']}|获取类型：{$task['get_type']}\n"; }
    break;
default:
        help();
    break;
}
break;
case 3:
switch ($argv[1]){
case "update": //更新大项
        switch ($argv[2]) {
            case "dellist":
                $newdellist=file_get_contents('https://github.com/kdwnil/MIX2TieBa/db/replacelist.json');
                file_put_contents(SYSTEM_ROOT . '/db/dellist.json',$newdellist);
                echo "已更新替换词列表\n";
            break;
            case "twtotb":
            break;
            case "check":
                $check=json_decode(file_get_contents("https://kdwnil.github.io/api/mix2tieba/check.json"),1);
            if($check["check_ver"] > CHECK_VER) {echo "系统更新 \n 版本号：".$check["system_ver"]."（".$check["check_ver"]."）\n 更新内容：".$check["check_data"]."\n"; }else{echo "系统更新 \n 无更新\n";}
            break;
            case "bduss":
                file_put_contents(SYSTEM_ROOT . '/settings.php','<?php' . "\r\n" . '$bduss=\'' . $argv[3] . '\';' . "\r\n" . '$cookie=\'BDUSS=\'.$bduss;');
                 echo "已更新您的BDUSS\n";
            break;
            default:
                help(); //你啥都不填怪我咯
                
        }
    break;
default:
        help();
    break;
}
}
