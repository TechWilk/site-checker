<?php

declare(strict_types=1);

namespace TechWilk\SiteChecks\UrlCheck;

use Ds\Queue;
use GuzzleHttp\Client;
use League\Uri\Http;
use TechWilk\SiteChecks\UrlCheck;

class FileExtention implements UrlCheck
{
    const ALLOWED_FILE_EXTENTIONS = [
        'html' => 'text/html',
        'xml' => 'application/xml',
        'js' => 'application/xml',
        'css' => 'application/css',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'webp' => 'image/webp',
        'gif' => 'image/gif',
        'gz' => '',
        'tar.gz' => '',
        'zip' => '',
    ];

    protected $tests = [
        'valid_file_extention' => null,
        'matches_mime_type' => null,
    ];

    public function run(Queue $queue, Http $uri, $response): array
    {
        $extention = pathinfo($uri->getPath(), PATHINFO_EXTENSION);

        if (empty($extention)) {
            return [
                'valid_file_extention' => true,
                'matches_mime_type' => null,
            ];
        }

        if (!array_key_exists($extention, self::ALLOWED_FILE_EXTENTIONS)) {
            return [
                'valid_file_extention' => false,
                'matches_mime_type' => null,
            ];
        }

        $mimeType = $response->getHeaderLine('Content-Type') ?? '';//self::ALLOWED_FILE_EXTENTIONS[$extention] ?? 'text/html';
        $mimeType = explode(';', $mimeType);
        $mimeType = $mimeType[0] ?? '';

        return [
            'valid_file_extention' => true,
            'matches_mime_type' => $mimeType === $response->getHeaderLine('Content-Type'),
        ];
    }
}
