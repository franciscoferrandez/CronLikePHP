<?php

$config = array();

$config_files = glob(__DIR__."/*.php", GLOB_BRACE);
foreach ($config_files as $config_file) {
    if (basename($config_file,".php") != basename(__FILE__,".php")) {
        $current_config = basename($config_file,".php");
        $config[$current_config] = require_once($config_file);
    }
}

