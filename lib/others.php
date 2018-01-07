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
/*发图接口*/
function tbpic($tbs,$path,$tbn,$mode,$cookie){
if($path==''){return '';}else{
$data=[
'filetype' => 'base64',
'file' => base64_encode(file_get_contents($path))
];
$a=json_decode(scurl("https://uploadphotos.baidu.com/upload/pic?tbs={$tbs}&fid=&save_yun_album=0",1,$data,$cookie,'http://tieba.baidu.com/f?kw='.$tbn,3),1);
if($a["err_no"]!=0){
return '';
}
//preg_match('/http:\/\/imgsrc.baidu.com\/tieba\/pic\/item\/(.+?).jpg/',$a["info"]["pic_water"],$b);
switch($mode){
case "ajax":
 return $a["info"]["pic_id_encode"].','.$a["info"]["fullpic_width"].','.$a["info"]["fullpic_height"].',false';
break;
default:
return '#(pic,'.$a["info"]["pic_id_encode"].','.$a["info"]["fullpic_width"].','.$a["info"]["fullpic_height"].')';
break;
}
}
}
