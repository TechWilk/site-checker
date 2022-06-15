<?php

declare(strict_types=1);

namespace TechWilk\SiteChecks\SiteCheck;

use GuzzleHttp\Client;
use TechWilk\SiteChecks\SiteCheck;

class WellKnownChangePassword implements SiteCheck
{
    protected $tests = [
        'redirect_exists',
    ];

    public function run(string $siteUrl): array
    {
        $client = new Client(['base_uri' => $siteUrl]);

        $response = $client->get('/.well-known/change-password', [
            'allow_redirects' => false,
            'http_errors' => false, // don't throw 404s as exceptions
        ]);

        return [
            'redirect_exists' => in_array($response->getStatusCode(), [301, 302]),
        ];
    }
}
