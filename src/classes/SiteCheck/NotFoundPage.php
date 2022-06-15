<?php

declare(strict_types=1);

namespace TechWilk\SiteChecks\SiteCheck;

use GuzzleHttp\Client;
use TechWilk\SiteChecks\SiteCheck;

class NotFoundPage implements SiteCheck
{
    protected $tests = [
        'page_exists',
    ];

    public function run(string $siteUrl): array
    {
        $client = new Client(['base_uri' => $siteUrl]);

        $response = $client->get('/404-page-test-invalid-url', [
            'http_errors' => false, // don't throw 404s as exceptions
        ]);

        return [
            'page_exists' => in_array($response->getStatusCode(), [404]),
        ];
    }
}
