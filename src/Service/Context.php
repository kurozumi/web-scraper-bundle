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
     * @return array|null
     */
    public function getData(string $url): ?array
    {
        /** @var ScraperInterface $scraper */
        foreach ($this->scrapers as $scraper) {
            $data = $scraper->getData($url);
            if ($data['items']->count() > 0) {
                return $data;
            }
        }

        return null;
    }
}