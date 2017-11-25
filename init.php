<?php
/**
 * KD·boT
 * Copyright (c) 2015~2017 NULLMIX&KD·Lab All Rights Reserved.
 * 
 */
define('SYSTEM_ROOT',dirname(__FILE__));
define('SYSTEM_VER','PHP-CLI2.0');
define('CHECK_VER','17112602');
define('SYSTEM_ISCONSOLE' , (isset($argv) ? true : false));
require(SYSTEM_ROOT.'/settings.php');
require(SYSTEM_ROOT.'/lib/scurl.php');
require(SYSTEM_ROOT.'/lib/others.php');
