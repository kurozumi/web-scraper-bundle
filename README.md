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
        
        $data = [];
        foreach ($feeds as $feed) {
            $items = $this->content->getItems($feed);
            if ($items->count() > 0) {
                foreach ($items as $item) {
                    switch ($item['scraper_name']) {
                        case RssScraper::class:
                            $data[] = [
                                'title' => $item['crawler']->filter('title')->text(),
                                'url' => $item['crawler']->filter('link')->text()
                            ];
                            break;
                    }
                }
            }
        }
        
        print_r($data);
    
        return Command::SUCCESS;
    }
}

```

## Custom Scraper

### Feed

```
<?php

namespace App\Service\Scraper;

use Kurozumi\WebScraperBundle\Service\ScraperInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class YouTubeFeedScraper implements ScraperInterface
{
    private HttpClientInterface $client;

    /**
     * @param HttpClientInterface $client
     */
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

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
        $response = $this->client->request('GET', $url);

        $items = new \ArrayIterator();
        $crawler = new Crawler($response->getContent());
        if ('feed' === $crawler->nodeName()) {
            $crawler->setDefaultNamespacePrefix('m', 'http://search.yahoo.com/mrss/');
            foreach ($crawler->filter('m|entry') as $item) {
                $items->append([
                    'scraper_name' => get_class($this),
                    'crawler' => new Crawler($item)
                ]);
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

use Kurozumi\WebScraperBundle\Service\ScraperInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HtmlScraper implements ScraperInterface
{
    private HttpClientInterface $client;

    /**
     * @param HttpClientInterface $client
     */
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

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
        $response = $this->client->request('GET', $url);

        $items = new \ArrayIterator();
        $crawler = new Crawler($response->getContent());
        if ('html' === $crawler->nodeName()) {
            foreach ($crawler->filter('ul > li') as $item) {
                $items->append([
                    'scraper_name' => get_class($this),
                    'crawler' => new Crawler($item)
                ]);
            }
        }
        return $items;
    }
}
```