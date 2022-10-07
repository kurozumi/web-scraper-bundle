<?php

namespace Kurozumi\WebScraperBundle\Service;

interface ScraperInterface
{
    /**
     * @param string $url
     * @return array
     */
    public function getData(string $url): array;
}