<?php
function translate ($type,$text,$from,$to){
switch ($type){
case "google":
return $translate_result = $use_Google_Translate == true ? (new GoogleTranslate) -> translate('auto', 'zh-CN', $text) : null;
break;
default:
$language=json_decode(scurl('https://www.translate.com/translator/ajax_lang_auto_detect',1,array("text_to_translate"=>$text),'','https://www.translate.com/',1),1)["language"]; 
return json_decode(scurl('https://www.translate.com/translator/ajax_translate',1,array("text_to_translate"=>$text,"source_lang"=>$language,"translated_lang"=>$to,"use_cache_only"=>false),'','https://www.translate.com/',1),1)["translated_text"];
}
}