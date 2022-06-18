<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use League\Uri\Contracts\UriException;
use League\Uri\Http;
use TechWilk\SiteChecks\Checks;

require __DIR__ . '/../templates/check.php';
require __DIR__ . '/../templates/report.php';

if (empty($_GET['site'])) {

    echo templateCheck('');

} else {

    try {
        $uri = Http::createFromString($_GET['site']);
    } catch (UriException $e) {
        echo templateCheck($_GET['site']);
        exit;
    }

    if (!$uri->getHost()) {
        echo templateCheck($_GET['site']);
        exit;
    }

    // var_dump($uri->getHost());exit;

    $checks = new Checks();

    $results = $checks->run($uri);

    echo templateReport($uri, $results);
}
