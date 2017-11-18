<?php
function help() {
    echo '帮助：以后再写';
}
//if(SYSTEM_ISCONSOLE)//挖个坑以后再填
switch ($argv[1]) {
    case "version": //检查版本号
        require dirname(__FILE__) . '/init.php';
        echo SYSTEM_VER . '(' . CHECK_VER . ')' . "\n";
    break;
    case "update": //更新大项
        switch ($argv[2]) {
            case "dellist":
            break;
            case "twtotb":
            break;
            case "bduss":
                $fp = fopen(SYSTEM_ROOT . '/settings.php', "w");
                flock($fp, LOCK_EX);
                fwrite($fp, '<?php' . "\r\n" . '$bduss=' . $argv[3] . ';' . "\n\r" . '$stoken=' . $argv[4] . ';' . "\r\n" . '$cookie=\'BDUSS=\'.$bduss.\';  STOKEN=.\'$stoken.\'; \';');
                fclose($fp, LOCK_UN);
            break;
            default:
                help(); //你啥都不填怪我咯
                
        }
    break;
    case "add": //增加一个任务
        
    break;
    case "del": //删除一个任务
        
    break;
    case "install": //安装
        if (file_exists(SYSTEM_ROOT . '/install/install.lock')) {
            die("您已安装本系统");
        }
        $fp = fopen(SYSTEM_ROOT . '/settings.php', "w");
        flock($fp, LOCK_EX);
        fwrite($fp, '<?php' . "\r\n" . '$bduss=' . $argv[2] . ';' . "\n\r" . '$stoken=' . $argv[3] . ';' . "\r\n" . '$cookie=\'BDUSS=\'.$bduss.\';  STOKEN=.\'$stoken.\'; \';');
        fclose($fp, LOCK_UN);
    break;
    case "check": //检查更新
        
    break;
    default:
        help();
    break;
}
