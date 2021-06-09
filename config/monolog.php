<?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$monologcfg = [
    "level" => Logger::NOTICE,
];


// create a log channel
$log = new Logger("CronLike");
$log->pushHandler(new StreamHandler(__DIR__."/../log/cron.log", $monologcfg["level"]));

$monologcfg["logger"] = $log;

return $monologcfg;