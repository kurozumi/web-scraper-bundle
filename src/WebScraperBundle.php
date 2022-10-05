<?php
declare(strict_types=1);

namespace Kurozumi\WebScraperBundle;

use Kurozumi\WebCrawlerBundle\Service\ScraperInterface;
use Kurozumi\WebScraperBundle\DependencyInjection\Compiler\ScraperPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class WebScraperBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function build(ContainerBuilder $container)
    {
        $container->registerForAutoconfiguration(ScraperInterface::class)
            ->addTag(ScraperPass::TAG);

        $container->addCompilerPass(new ScraperPass());
    }
}