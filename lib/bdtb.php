<?php
/** 
* KD·boT 
* Copyright (c) 2015~2018 NULLMIX&KD·Lab All Rights Reserved. *  
**/
function bdtb($task,$text,$tbpic,$tbs,$cookie){
switch ($task["post_type"]) {
            case "ajax":
                $fid = json_decode(file_get_contents('http://tieba.baidu.com/f/commit/share/fnameShareApi?ie=utf-8&fname=' . $task["tbn"]) , 1) ["data"]["fid"];
                $data = ["co" => $task["name"] . "：\n" . $text, "_t" => time() , "tag" => 11, "upload_img_info" => $tbpic, "fid" => $fid, "src" => 1, "word" => $task["tbn"], "tbs" => $tbs, "z" => $task["tid"]];
                $a = json_decode(scurl('https://tieba.baidu.com/mo/q/apubpost', 1, $data, $cookie, 'http://tieba.baidu.com/p/' . $task["tid"] . '?pn=0&', 1) , 1);
                /*我也忘记下面这个拿来干嘛了*/
                scurl('https://tieba.baidu.com/mo/q/m?kz=' . $task["tid"] . '&last=1&has_url_param=0&is_ajax=1&post_type=normal&_t=1509825073800', 0, '', $cookie, 'http://tieba.baidu.com/p/' . $task["tid"] . '?pn=0&', 1);
                return array(
                    time() ,
                    $a,
                    $task["name"],
                    $text
                );
                if ($a['no'] != "0") {
                    echo $task["name"] . "：错误代码#" . $a['no'] . ',错误原因：' . $a['error'] . "\n";
                } else {
                    echo $task["name"] . '：发送成功' . "\n";
                }
                break;
            default:
                $t = scurl('http://tieba.baidu.com/mo/m?kz=' . $task["tid"], 0, '', $cookie, '', 'kdcloud automatic bot');
                preg_match('/<form action=\"(.*?)\" method=\"post\">/', $t, $formurl);
                preg_match('/<input type=\"hidden\" name=\"ti\" value=\"(.*?)\"\/>/', $t, $ti);
                preg_match('/<input type=\"hidden\" name=\"src\" value=\"(.*?)\"\/>/', $t, $src);
                preg_match('/<input type=\"hidden\" name=\"word\" value=\"(.*?)\"\/>/', $t, $word);
                preg_match('/<input type=\"hidden\" name=\"tbs\" value=\"(.*?)\"\/>/', $t, $tbs);
                preg_match('/<input type=\"hidden\" name=\"fid\" value=\"(.*?)\"\/>/', $t, $fid);
                preg_match('/<input type=\"hidden\" name=\"floor\" value=\"(.*?)\"\/>/', $t, $floor);
                $data = ["co" => $task["name"] . '：' . $text, "ti" => $GLOBALS['ti'][1], "src" => $GLOBALS["src"][1], "word" => $GLOBALS["word"][1], "tbs" => $GLOBALS["tbs"][1], "ifpost" => 1, "ifposta" => 0, "post_info" => 0, "tn" => "baiduWiseSubmit", "fid" => $GLOBALS["fid"][1], "verify" => "", "verify_2" => "", "pinf" => "1_2_0", "pic_info" => "", "z" => $task["tid"], "last" => 0, "pn" => 0, "r" => 0, "see_lz" => 0, "no_post_pic" => 0, "floor" => $GLOBALS["floor"][1], "sub1" => "%E5%9B%9E%E8%B4%B4"];
                $a = scurl('http://tieba.baidu.com' . $GLOBALS["formurl"][1], 1, $data, $cookie, 'http://tieba.baidu.com/mo/m?kz=' . $task["tid"], 'kdcloud automatic bot');
                if (!preg_match('/<span class=\"light\">回贴成功<\/span>/', $a)) {
                    preg_match('/<div class=\"d\">(.+?)<\/div>/', $a, $whyerror);
                    $result = $whyerror[1];
                } else {
                    $result = '发送成功';
                }
                echo $task["name"] . '：' . $result . "\n";
                return array(
                    time() ,
                    $result,
                    $task["name"],
                    $text
                );
                break;
        
    }
}