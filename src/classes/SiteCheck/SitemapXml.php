<?php

declare(strict_types=1);

namespace TechWilk\SiteChecks\SiteCheck;

use GuzzleHttp\Client;
use League\Uri\Contracts\UriException;
use League\Uri\Uri;
use TechWilk\SiteChecks\SiteCheck;
use TechWilk\SiteChecks\Traits\ParseTextFile;
use Symfony\Component\DomCrawler\Crawler;

class SitemapXml implements SiteCheck
{
    use ParseTextFile;

    const SITEMAP_NAMESPACE = 'http://www.sitemaps.org/schemas/sitemap/0.9';
    const XML_MIME_TYPES = ['application/xml', 'application/xml;charset=UTF-8'];

    protected $tests = [
        'file_exists' => null,
        'xml_mime_type' => null,
        'urlset_tag_present' => null,
        'namespace_present' => null,
        'url_tag_present' => null,
        'loc_tag_present' => null,

        'sitemapindex_tag_present' => null,
    ];

    public function run(string $siteUrl): array
    {
        $client = new Client(['base_uri' => $siteUrl]);

        $sitemapUri = $this->sitemapUriFromRobots($client);
        if (!$sitemapUri) {
            $sitemapUri = '/sitemap.xml';
        }
        // var_dump((string)$sitemapUri);
        $response = $client->get((string)$sitemapUri, [
            'http_errors' => false, // don't throw 404s as exceptions
        ]);

        $this->tests['file_exists'] = in_array($response->getStatusCode(), [200]);

        if (!$this->tests['file_exists']) {
            return $this->tests;
        }

        $this->tests['xml_mime_type'] = in_array($response->getHeaderLine('Content-Type'), self::XML_MIME_TYPES);

        // $tests = $this->sitemapChecks((string) $response->getBody());
        // var_dump($tests);exit;

        return [
            'file_exists' => in_array($response->getStatusCode(), [200]),
            'xml_mime_type' => in_array($response->getHeaderLine('Content-Type'), ['application/xml', 'application/xml;charset=UTF-8']),
        ];
    }

    protected function sitemapUriFromRobots(Client $client): ?Uri
    {
        $response = $client->get('/robots.txt', [
            'http_errors' => false, // don't throw 404s as exceptions
        ]);

        if (!in_array($response->getStatusCode(), [200])) {
            return null;
        }

        $parsedFile = $this->parseTextFile((string) $response->getBody());

        if (!array_key_exists('Sitemap', $parsedFile)) {
            return null;
        }

        if (empty(trim($parsedFile['Sitemap'][0]))) {
            return null;
        }

        try {
            $uri = Uri::createFromString($parsedFile['Sitemap'][0]);
        } catch (UriException $e) {
            return null;
        }

        return $uri;
    }

    protected function sitemapChecks(string $responseBody): array {
        $crawler = new Crawler();

        var_dump($responseBody);

        $crawler->addXmlContent($responseBody);

        $this->sitemapIndexChecks($crawler);
        var_dump($this->tests);exit;

        try {
            $namespace = $crawler->filterXPath('//default:urlset');//->attr('xmlns');

            // $namespace = $crawler->filterXPath('//default:sitemapindex')->attr('xmlns');
            var_dump($namespace);exit;

        } catch (\InvalidArgumentException $e) {
            var_dump($e::class);
            var_dump($e->getMessage());
            exit;
        }


        // 'urlset_tag_present',
        // 'namespace_present',
        // 'url_tag_present',
        // 'loc_tag_present',

    }

    protected function sitemapIndexChecks(Crawler $crawler): void
    {
        try {
            $sitemapindex = $crawler->filterXPath('//default:sitemapindex');
            // var_dump($namespace);exit;
            $this->tests['sitemapindex_tag_present'] = true;
            $this->tests['namespace_present'] = ($sitemapindex->attr('xmlns') === self::SITEMAP_NAMESPACE);

        } catch (\InvalidArgumentException $e) {
            $this->tests['sitemapindex_tag_present'] = false;
            return;
        }

        try {
            $sitemaps = $crawler->filterXPath('//default:sitemapindex/sitemap');
            var_dump('sitemaps_______');
            foreach ($sitemaps as $node) {

                // var_dump($node->filterXPath('//default:loc'));
            }
            exit;
            $this->tests['sitemap_tag_present'] = true;

        } catch (\InvalidArgumentException $e) {
            $this->tests['sitemap_tag_present'] = false;
            return;
        }

        // sitemap
        // loc
    }
}
