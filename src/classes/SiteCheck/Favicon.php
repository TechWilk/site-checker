<?php

declare(strict_types=1);

namespace TechWilk\SiteChecks\SiteCheck;

use GuzzleHttp\Client;
use TechWilk\SiteChecks\SiteCheck;

class Favicon implements SiteCheck
{
    protected $tests = [
        'file_exists',
        'legacy_ico_file',
        'svg_file',
    ];

    public function run(string $siteUrl): array
    {
        $client = new Client(['base_uri' => $siteUrl]);

        $icoResponse = $client->get('/favicon.ico', [
            'http_errors' => false, // don't throw 404s as exceptions
        ]);

        $svgResponse = $client->get('/favicon.ico', [
            'http_errors' => false, // don't throw 404s as exceptions
        ]);

        if (
            !in_array($icoResponse->getStatusCode(), [200])
            && !in_array($svgResponse->getStatusCode(), [200])
            ) {
            return [
                'file_exists' => in_array($icoResponse->getStatusCode(), [200]) || in_array($svgResponse->getStatusCode(), [200]),
                'legacy_ico_file' => null,
                'svg_file' => null,
            ];
        }

        return [
            'file_exists' => in_array($icoResponse->getStatusCode(), [200]) || in_array($svgResponse->getStatusCode(), [200]),
            'legacy_ico_file' => in_array($icoResponse->getStatusCode(), [200]),
            'svg_file' => in_array($svgResponse->getStatusCode(), [200]),
        ];
    }
}
