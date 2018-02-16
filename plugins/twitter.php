<?php
/*twitter plugin*/
function twitter_get($check, $name, $tbn, $post_type, $tbs, $cookie)
{
    $autoapi = json_decode(file_get_contents('https://twitter.com/i/profiles/show/' . $name . '/timeline/tweets?composed_count=0&include_available_features=0&include_entities=0&include_new_items_bar=true&interval=30000&latent_count=0&min_position=' . $check), 1);
    $tweetid = $autoapi["max_position"];
    if ($tweetid == '') {
        return '';
    } else {
        echo $name . '：正在发送' . "\n";
        $api = $autoapi["items_html"];
        preg_match_all('/<p(.+?)>(.+?)<\\/p>/s', $api, $kd1);
        //preg_match_all('/data-screen-name=\"(.+?)\" data-name=\"(.+?)\" data-user-id=\"(.+?)\"/', $api, $kd2);
        $text = '';
        foreach ($kd1[2] as $newkd1) {
            $text .= $newkd1 . "\n";
        }
        $tbpic = '';//效率、成功率过低，暂停该功能
        /*		if(preg_match_all('/pic.twitter.com/',$text) && $post_type=="ajax"){
        			preg_match_all('/https:\/\/pbs.twimg.com\/media\/(.+?).jpg/',$api,$kd3);
        			for($piccount=0;
        			$piccount<count($kd3[0]);
        			$piccount+=2){
        				$tbpic.="|".tbpic($tbs,$kd3[0][$piccount],$tbn,"ajax",$cookie);
        			}
        			echo $name.'的图片数据：'.$tbpic."\n";
        		}*/
        return array("text" => $text, "tbpic" => $tbpic, "tweetid" => $tweetid);
    }
}
