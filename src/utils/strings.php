<?php
namespace Utils;

class Strings {

    public static function validCronTabJob($line) {
        $line = preg_replace('/\s\s+/', ' ', trim($line));
        if ((strlen($line) == 0) ||($line[0] == '#') || (strlen($line) < 10)) {
            return false;
        }
        return $line;
    }

    public static function getCronJob($line) {
        $cronjob = array();
        preg_match_all('/\s/', $line,$matches, PREG_OFFSET_CAPTURE);  

        if (count($matches[0]) >= 5) {
            $cronjob["expression"] = substr($line, 0, $matches[0][4][1]+1);
            $cronjob["command"] = substr($line, $matches[0][4][1]+1);

            $cron = new \Cron\CronExpression($cronjob["expression"]);
            $cronjob["nextrun"] = $cron->getNextRunDate();//->format('Y-m-d H:i:s');
        }

        return $cronjob;
    }

}