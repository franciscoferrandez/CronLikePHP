<?php
define("APPDIR",__DIR__);

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/config/autoload.php';

$cron = new \Classes\CronIterator($config["monolog"]["logger"]);
$cron->setConfig($config);
$cron->run();

