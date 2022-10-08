<?php

namespace Kurozumi\WebScraperBundle\Service\Scraper;

use Kurozumi\WebScraperBundle\Service\ScraperInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class AbstractScraper implements ScraperInterface
{
    protected HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $url
     * @return ResponseInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getResponse(string $url): ResponseInterface
    {
        return $this->client->request('GET', $url);
    }

    /**
     * @param ResponseInterface $response
     * @return Crawler
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getCrawler(ResponseInterface $response): Crawler
    {
        return new Crawler($response->getContent());
    }

    /**
     * @param \DOMElement $element
     * @return Crawler
     */
    public function getItem(\DOMElement $element): Crawler
    {
        return new Crawler($element);
    }
}