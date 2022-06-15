<?php

declare(strict_types=1);

namespace TechWilk\SiteChecks;

use Ds\Queue;
use GuzzleHttp\Client;
use League\Uri\Http;
use League\Uri\UriInfo;

class Checks
{
    protected $checks = [
        'url' => [
            'file_extention' => 'TechWilk\\SiteChecks\\UrlCheck\\FileExtention',
            'status_code' => 'TechWilk\\SiteChecks\\UrlCheck\\StatusCode',

        ],
        'site' => [
            'not_found_page' => 'TechWilk\\SiteChecks\\SiteCheck\\NotFoundPage',
            'robots_txt' => 'TechWilk\\SiteChecks\\SiteCheck\\RobotsTxt',
            'favicon' => 'TechWilk\\SiteChecks\\SiteCheck\\Favicon',
            'sitemap_xml' => 'TechWilk\\SiteChecks\\SiteCheck\\SitemapXml',
            'well_known_change_password' => 'TechWilk\\SiteChecks\\SiteCheck\\WellKnownChangePassword',
            'well_known_security_txt' => 'TechWilk\\SiteChecks\\SiteCheck\\WellKnownSecurityTxt',
        ],
        'domain' => [

        ]
    ];

    public function run(Http $initialUri)
    {
        $results = [];

        foreach ($this->checks['domain'] as $name => $class) {
            try {
                $domain = $initialUri->getHost();

                $check = new $class();
                $results['domain'][$name] = $check->run($domain);
            } catch (\Exception $e) {
                $results['domain'][$name] = ['error' => $e->getMessage()];
            }
        }

        $siteRoot = UriInfo::getOrigin($initialUri);
        foreach ($this->checks['site'] as $name => $class) {
            try {

                $check = new $class();
                $results['site'][$name] = $check->run($siteRoot);
            } catch (\Exception $e) {
                $results['site'][$name] = ['error' => $e->getMessage()];
            }
        }

        $queue = new Queue();
        $queue->push($initialUri);

        $client = new Client(['base_uri' => $siteRoot]);
        while ($queue->count() > 0) {
            $uri = $queue->pop();
            $response = $client->get((string) $uri, [
                'http_errors' => false, // don't throw 404s as exceptions
            ]);
            foreach ($this->checks['url'] as $name => $class) {
                try {
                    $check = new $class();
                    $results['url'][$name] = $check->run($queue, $uri, $response);
                } catch (\Exception $e) {
                    $results['url'][$name] = ['error' => $e->getMessage()];
                }
            }
        }

        return $results;
    }
}
