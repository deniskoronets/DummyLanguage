<?php

/**
 * Cli-only
 */

require __DIR__ . '/../vendor/autoload.php';

if ($argc != 2) {
    exit('Invalid amount of arguments. Specify file to run script on');
}

$dummy = new \src\DummyLanguage();

if (!file_exists($argv[1])) {
    exit('File doesnt exist');
}

$dummy->run(file_get_contents($argv[1]));