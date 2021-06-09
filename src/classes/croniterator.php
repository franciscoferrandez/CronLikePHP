<?php

namespace Classes;

class CronIterator extends \BaseClass\Singleton
{
    private $log;

    private $config;
    private $interval;
    private $crontabfile;

    public function __construct(\Monolog\Logger $log) {
        $this->log          = $log;
    }

    public function setConfig($config) {
        $this->config       = $config;
        $this->interval     = $config["app"]["interval"];
        $this->crontabfile  = $config["app"]["crontabfile"];
    }

    public function logCrontab($jobs) {
        $this->log->notice("CRONTAB LOADED");
        foreach ($jobs as $job) {
            $this->log->notice(sprintf("%s (%s) :: \"%s\"", $job["expression"], ($job["nextrun"])->format('Y-m-d H:i:s'), $job["command"]));
        }
    }

    public function run () {

        $this->log->error('Cron iterator starts, iterating every ' . $this->interval);

        $this->interval = 1 / 60 * $this->interval;
        set_time_limit( 0 );

        $schedule = \Classes\Schedule::getInstance();
        $runner = \Classes\Runner::getInstance();
        
        while ( 1 ){

            $updateCrontab = $schedule->loadFromFile(APPDIR. "/" . $this->crontabfile);
            if ($updateCrontab) $this->logCrontab($schedule->getAll());

            $sleep = $this->interval*60+(time());

            if(time() != $sleep) {
                time_sleep_until($sleep); 
            }

            $jobToExecute = $schedule->getJobsToExecute();
            $runner->execute($jobToExecute);
            
        }
    }
}