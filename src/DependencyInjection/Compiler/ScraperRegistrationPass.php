<?php

namespace Kurozumi\WebScraperBundle\DependencyInjection\Compiler;

use Kurozumi\WebScraperBundle\Service\Context;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ScraperRegistrationPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    const TAG = 'kurozumi.web_scraper';

    public function process(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(Context::class);

        foreach($this->findAndSortTaggedServices(self::TAG, $container) as $id) {
            $definition->addMethodCall('addScraper', [new Reference($id)]);
        }
    }
}