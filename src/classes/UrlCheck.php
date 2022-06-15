<?php

declare(strict_types=1);

namespace TechWilk\SiteChecks;

use Ds\Queue;
use League\Uri\Http;

interface UrlCheck
{
    public function run(Queue $queue, Http $uri, $response): array;
}
