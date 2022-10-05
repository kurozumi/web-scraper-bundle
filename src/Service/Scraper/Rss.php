<?php

namespace Kurozumi\WebScraperBundle\Service\Scraper;

use Kurozumi\WebScraperBundle\Service\ScraperInterface;
use Symfony\Component\DomCrawler\Crawler;

class Rss implements ScraperInterface
{
    /**
     * @param string $url
     * @return \ArrayIterator
     * @throws \Exception
     */
    public function getItems(string $url): \ArrayIterator
    {
        if (false === $data = file_get_contents($url)) {
            throw new \Exception();
        }

        $items = new \ArrayIterator();
        $crawler = new Crawler($data);
        if ('rss' === $crawler->nodeName()) {
            foreach ($crawler->filter('item') as $item) {
                $items->append([
                    'scraper_name' => get_class($this),
                    'crawler' => new Crawler($item)
                ]);
            }
        }

        return $items;
    }
}