<?php

namespace Classes;

class Schedule extends \BaseClass\Singleton
{
    private $lastTouched = 0;
    private $hashmap = [];

    public function getValue(string $key): string
    {
        return $this->hashmap[$key];
    }

    public function setValue(string $key, $value): void
    {
        $this->hashmap[$key] = $value;
    }

    private function resetHashMap() {
        $this->hashmap = [];
    }

    public function getAll() {
        return $this->hashmap;
    }

    public function getJobsToExecute() {
        $toExecute = array();
        foreach ($this->hashmap as $line => &$job) {
            if (($job["nextrun"])->getTimestamp() <= time()) {
                $job = \Utils\Strings::getCronJob($line);
                $toExecute[] = $job;
            } 
            
        }
        return $toExecute;
    }

    public function loadFromFile($filename) {

        // si crontab no existe, vaciar lista de jobs y salir
        if (!file_exists($filename)) {
            $this->resetHashMap();
            die("crontab not found");
        } 
        
        // si la fecha de edición es inferior o igual a la ultima leida, salir
        clearstatcache();
        $mod_date = filemtime($filename);

        if ($mod_date <= $this->lastTouched) {
            return false;
        } else {
            $this->lastTouched = $mod_date;
        }

        // si continua es porque acabamos de arrancar o se ha modificado el archivo
        $this->resetHashMap();

        $filecontent = file_get_contents($filename);

        foreach(preg_split("/((\r?\n)|(\r\n?))/", $filecontent) as $line){
            $line = \Utils\Strings::validCronTabJob($line);
            if ($line) {
                $this->setValue($line, \Utils\Strings::getCronJob($line));
            }
        } 

        return true;
    }
}