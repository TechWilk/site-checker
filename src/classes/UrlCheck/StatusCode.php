<?php

declare(strict_types=1);

namespace TechWilk\SiteChecks\UrlCheck;

use Ds\Queue;
use League\Uri\Http;
use TechWilk\SiteChecks\UrlCheck;

class StatusCode implements UrlCheck
{
    protected $tests = [
        'valid_status_code',
    ];

    public function run(Queue $queue, Http $uri, $response): array
    {
        return [
            'valid_status_code' => in_array($response->getStatusCode(), [200]),
        ];
    }
}
