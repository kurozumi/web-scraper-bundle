<?php

namespace Kurozumi\WebCrawlerBundle\Service;

interface ScraperInterface
{
    /**
     * @param string $url
     * @return \ArrayIterator
     */
    public function getItems(string $url): \ArrayIterator;
}