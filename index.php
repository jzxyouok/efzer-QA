<?php
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
define('APP_PATH','./Application/');
define('RUNTIME_PATH','./Runtime/');
define('APP_DEBUG',True);
define('BUILD_DIR_SECURE', false);
require './ThinkPHP/ThinkPHP.php';
