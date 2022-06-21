<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use League\Uri\Contracts\UriException;
use League\Uri\Http;
use TechWilk\SiteChecks\Checks;

require __DIR__ . '/utils.php';
require __DIR__ . '/../templates/check.php';
require __DIR__ . '/../templates/report.php';
require __DIR__ . '/../templates/rateLimit.php';

if (empty($_GET['site'])) {

    echo templateCheck('', '');

} else {

    try {
        $uri = Http::createFromString($_GET['site']);
    } catch (UriException $e) {
        echo templateCheck($_GET['site'], $e->getMessage());
        exit;
    }

    if (!$uri->getHost()) {
        echo templateCheck($_GET['site'], 'Invalid url');
        exit;
    }

    $file = __DIR__ . '/../logs/rate-limit.log';

    // basic rate limiting to reduce abuse
    $now = new DateTimeImmutable();
    $comparisonDate = $now->modify('-1min');

    $lastLine = getLastLineOfFile($file);
    $parts = explode(' - ', $lastLine);

    if (count($parts) > 1) {
        $lastRun = new DateTimeImmutable($parts[0]);

        if ($lastRun >= $comparisonDate) {

            echo templateRateLimit($uri);
            exit;
        }
    }

    $line = $now->format('c') . ' - ' . (string)$uri;
    writeLineToLogFile($file, $line);

    $checks = new Checks();

    $results = $checks->run($uri);

    echo templateReport($uri, $results);
}
