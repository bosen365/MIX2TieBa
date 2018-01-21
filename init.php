<?php
/**
 * KD·boT
 * Copyright (c) 2015~2017 NULLMIX&KD·Lab All Rights Reserved.
 * 
 */
define('SYSTEM_ROOT',dirname(__FILE__));
define('PLUGINS_ROOT',dirname(__FILE__).'/plugins');
define('SYSTEM_VER','BETA7');
define('CHECK_VER','18012102');
define('SYSTEM_ISCONSOLE' , (isset($argv) ? true : false));
require(SYSTEM_ROOT.'/settings.php');
require(SYSTEM_ROOT.'/lib/scurl.php');
require(SYSTEM_ROOT.'/lib/others.php');
require(SYSTEM_ROOT.'/lib/array.php');
require(PLUGINS_ROOT.'/twitter.php');
require(PLUGINS_ROOT.'/weibo.php');

