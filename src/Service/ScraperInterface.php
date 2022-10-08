<?php

namespace Kurozumi\WebScraperBundle\Service;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\ResponseInterface;

interface ScraperInterface
{
    /**
     * @param string $url
     * @return \ArrayIterator
     */
    public function getItems(string $url): \ArrayIterator;

    /**
     * @param string $url
     * @return ResponseInterface
     */
    public function getResponse(string $url): ResponseInterface;

    /**
     * @param ResponseInterface $response
     * @return Crawler
     */
    public function getCrawler(ResponseInterface $response): Crawler;

    /**
     * @param \DOMElement $element
     * @return Crawler
     */
    public function getItem(\DOMElement $element): Crawler;
}