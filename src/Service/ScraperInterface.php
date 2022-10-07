<?php

namespace Kurozumi\WebScraperBundle\Service;

interface ScraperInterface
{
    /**
     * @param string $url
     * @return \ArrayIterator
     */
    public function getItems(string $url): \ArrayIterator;
}