<?php

declare(strict_types=1);

namespace TechWilk\SiteChecks\SiteCheck;

use GuzzleHttp\Client;
use TechWilk\SiteChecks\SiteCheck;

class RobotsTxt implements SiteCheck
{
    protected $tests = [
        'file_exists',
        'text_mime_type'
    ];

    public function run(string $siteUrl): array
    {
        $client = new Client(['base_uri' => $siteUrl]);

        $response = $client->get('/robots.txt', [
            'http_errors' => false, // don't throw 404s as exceptions
        ]);

        return [
            'file_exists' => in_array($response->getStatusCode(), [200]),
            'text_mime_type' => in_array($response->getHeaderLine('Content-Type'), ['text/plain', 'text/plain;charset=UTF-8']),
        ];
    }
}
