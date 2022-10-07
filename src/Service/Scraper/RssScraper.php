<?php

namespace Kurozumi\WebScraperBundle\Service\Scraper;

use Kurozumi\WebScraperBundle\Service\ScraperInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RssScraper implements ScraperInterface
{
    private HttpClientInterface $client;

    /**
     * @param HttpClientInterface $client
     */
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $url
     * @return \ArrayIterator
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getItems(string $url): \ArrayIterator
    {
        $response = $this->client->request('GET', $url);

        $items = new \ArrayIterator();
        $crawler = new Crawler($response->getContent());
        if ('rss' === $crawler->nodeName()) {
            foreach ($crawler->filter('item') as $item) {
                $items->append(new Crawler($item));
            }
        }

        return $items;
    }
}