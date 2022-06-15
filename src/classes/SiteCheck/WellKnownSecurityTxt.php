<?php

declare(strict_types=1);

namespace TechWilk\SiteChecks\SiteCheck;

use DateTime;
use Exception;
use GuzzleHttp\Client;
use TechWilk\SiteChecks\SiteCheck;
use TechWilk\SiteChecks\Traits\ParseTextFile;

class WellKnownSecurityTxt implements SiteCheck
{
    use ParseTextFile;

    protected $tests = [
        'file_exists',
        'text_mime_type',
        'contact_field_present',
        'expires_field_present',
        'expires_date_in_future',
    ];

    public function run(string $siteUrl): array
    {
        $client = new Client(['base_uri' => $siteUrl]);

        $response = $client->get('/.well-known/security.txt', [
            'http_errors' => false, // don't throw 404s as exceptions
        ]);

        if (!in_array($response->getStatusCode(), [200])) {
            return [
                'file_exists' => false,
                'text_mime_type' => null,
                'contact_field_present' => null,
                'expires_field_present' => null,
                'expires_date_in_future' => null,
            ];
        }

        $parsedFile = $this->parseTextFile((string) $response->getBody());

        if (!array_key_exists('Expires', $parsedFile)) {
            return [
                'file_exists' => in_array($response->getStatusCode(), [200]),
                'text_mime_type' => in_array($response->getHeaderLine('Content-Type'), ['text/plain', 'text/plain;charset=UTF-8']),
                'contact_field_present' => array_key_exists('Contact', $parsedFile),
                'expires_field_present' => array_key_exists('Expires', $parsedFile),
                'expires_date_in_future' => null,
            ];
        }

        try {
            $date = new DateTime($parsedFile['Expires'][0]);
        } catch (Exception $e) {
            return [
                'file_exists' => in_array($response->getStatusCode(), [200]),
                'text_mime_type' => in_array($response->getHeaderLine('Content-Type'), ['text/plain', 'text/plain;charset=UTF-8']),
                'contact_field_present' => array_key_exists('Contact', $parsedFile),
                'expires_field_present' => array_key_exists('Expires', $parsedFile),
                'expires_date_in_future' => false,
            ];
        }

        return [
            'file_exists' => in_array($response->getStatusCode(), [200]),
            'text_mime_type' => in_array($response->getHeaderLine('Content-Type'), ['text/plain', 'text/plain;charset=UTF-8']),
            'contact_field_present' => array_key_exists('Contact', $parsedFile),
            'expires_field_present' => array_key_exists('Expires', $parsedFile),
            'expires_date_in_future' => ($date > new DateTime()),
        ];
    }
}
