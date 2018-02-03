<?php
/**
 * KD·boT
 * Copyright (c) 2015~2018 NULLMIX&KD·Lab All Rights Reserved.
 * 
 */
define('SYSTEM_ROOT',dirname(__FILE__));
define('PLUGINS_ROOT',dirname(__FILE__).'/plugins');
define('SYSTEM_VER','BETA8');
define('CHECK_VER','18020401');
define('SYSTEM_ISCONSOLE' , (isset($argv) ? true : false));
require(SYSTEM_ROOT.'/lib/scurl.php');
require(SYSTEM_ROOT.'/lib/others.php');
require(SYSTEM_ROOT.'/lib/bdtb.php');
require(PLUGINS_ROOT.'/twitter.php');
require(PLUGINS_ROOT.'/weibo.php');
require(PLUGINS_ROOT.'/translate.php');
$settings=json_decode(file_get_contents(SYSTEM_ROOT.'/db/settings.json'),1);
$bduss=$settings["bduss"];
$cookie='BDUSS='.$bduss;
