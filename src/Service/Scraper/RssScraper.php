<?php

namespace Kurozumi\WebScraperBundle\Service\Scraper;

final class RssScraper extends AbstractScraper
{
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
        $response = $this->getResponse($url);

        $items = new \ArrayIterator();
        $crawler = $this->getCrawler($response);
        if ('rss' === $crawler->nodeName()) {
            foreach ($crawler->filter('item') as $item) {
                $items->append($this->getItem($item));
            }
        }

        return $items;
    }
}