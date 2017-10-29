<? php
/**
 * KD·boT
 * Copyright (c) 2015~2017 NULLMIX&KDCloud All Rights Reserved.
 * 
 */
define('SYSTEM_ROOT',dirname(__FILE__));
define('SYSTEM_VER','SHELL2.0');
define('CHECK_VER','17102901');
define('SYSTEM_ISCONSOLE' , (isset($argv) ? true : false));
require(SYSTEM_ROOT.'/lib/scurl.php');
//require(SYSTEM_ROOT.'/lib/others.php');
//require(SYSTEM_ROOT.'/lib/msg.php');