<?php

namespace Classes;

class Runner extends \BaseClass\Singleton
{

    public function execute($jobs) {
        foreach ($jobs as $line => &$job) {
            echo sprintf("%s: \t \"%s\" (next run: %s)\n", "Now: ".date("H:i:s")." || ".$job["expression"], $job["command"], ($job["nextrun"])->format('Y-m-d H:i:s'));
        }
    }
}