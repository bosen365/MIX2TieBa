<?php
function help() {
    echo "帮助（简体中文版）\n";
}
function fp($path,$type,$text){
    $fp = fopen($path,$type);
        flock($fp, LOCK_EX);
        fwrite($fp, $text);
        fclose($fp);
}