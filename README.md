# WebScraperBundle

Manage multiple Web Scraper bundle for Symfony.

## Install

```
composer req kurozumi/web-scraper-bundle
```

## How to use

```
namespace App\Command;

use Kurozumi\WebScraperBundle\Service\Context;
use Kurozumi\WebScraperBundle\Service\Scraper\RssScraper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;

#[AsCommand(
    name: 'app:scraper'
)]
class ScraperCommand extends Command
{
    private Context $context;
    
    public function __construct(Context $context)
    {
        $this->context = $context;
        
        parent::__construct();
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $feeds = [
            'https://aaa.rss.xml',
            'https://bbb.rss.xml'
        ];
        
        $items = [];
        foreach ($feeds as $feed) {
            $data = $this->content->getData($feed);
            if (null !== $data && $data['items']->count() > 0) {
                foreach ($data['items'] as $item) {
                    switch ($data['name']) {
                        case RssScraper::class:
                            $items[] = [
                                'title' => $item->filter('title')->text(),
                                'url' => $item->filter('link')->text()
                            ];
                            break;
                    }
                }
            }
        }
        
        print_r($items);
    
        return Command::SUCCESS;
    }
}

```

## Custom Scraper

### Feed

```
<?php

namespace App\Service\Scraper;

final class YouTubeFeedScraper extends AbstractScraper
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
        if ('feed' === $crawler->nodeName()) {
            $crawler->setDefaultNamespacePrefix('m', 'http://search.yahoo.com/mrss/');
            foreach ($crawler->filter('m|entry') as $item) {
                $items->append($this->getItem($item));
            }
        }

        return $items;
    }
}
```

### Html

```
<?php

namespace App\Service\Scraper;

final class HtmlScraper extends AbstractScraper
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
        if ('html' === $crawler->nodeName()) {
            foreach ($crawler->filter('ul > li') as $item) {
                $items->append($this->getItem($item));
            }
        }
        
        return $items;
    }
}
```