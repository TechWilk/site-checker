<?php

require __DIR__ . '/../vendor/autoload.php';

use TechWilk\SiteChecks\Checks;
use League\Uri\Contracts\UriException;
use League\Uri\Http;

if (empty($argv[1])) {
    echo 'Provide url as first parameter';
    exit;
}

try {
    $uri = Http::createFromString($argv[1]);
} catch (UriException $e) {
    echo 'Invalid url';
    exit;
}

$checks = new Checks();

$results = $checks->run($uri);

// var_dump($results);

foreach ($results as $type => $checks) {
    echo '-----'.PHP_EOL;
    echo $type.PHP_EOL;
    echo '-----'.PHP_EOL;
    foreach ($checks as $name => $tasks) {
        echo '- '.$name.PHP_EOL;
        foreach ($tasks as $taskName => $result) {
            echo '  |'.($result === true ? 'PASSED' : ($result === null ? '      ' :'FAILED')).'| '.$taskName.PHP_EOL;
        }
        echo PHP_EOL;
    }
}

$overallStatus = array_reduce($results['site'], function ($failedCount, $checkResults) {

    $tasksFailed = array_reduce($checkResults, function ($taskFailedCount, $result) {
        return $taskFailedCount + ($result === false ? 0.0001 : 0);
    }, 0);

    return $failedCount + ($tasksFailed > 0 ? (1 + $tasksFailed) : 0);
}, 0);

echo ($overallStatus === 0 ? 'All checks PASSED' : $overallStatus.' checks FAILED') . PHP_EOL;
