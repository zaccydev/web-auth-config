#!/usr/bin/php
<?php

use WebAuthConfHld\WebAuthConf;

require 'vendor/autoload.php';
define('__CONF_FILE__', 'src/wac-conf.json');
define('__VERSION__', '1.0');

$application = new WebAuthConf();
$application->run();