<?php

namespace Kurozumi\WebScraperBundle\Service;

class Context
{
    private array $scrapers = [];

    /**
     * @param ScraperInterface $scraper
     * @return void
     */
    public function addScraper(ScraperInterface $scraper): void
    {
        $this->scrapers[] = $scraper;
    }

    /**
     * @param string $url
     * @return \ArrayIterator
     */
    public function getItems(string $url): \ArrayIterator
    {
        /** @var ScraperInterface $scraper */
        foreach ($this->scrapers as $scraper) {
            $items = $scraper->getItems($url);
            if ($items->count() > 0) {
                return $items;
            }
        }

        return new \ArrayIterator();
    }
}