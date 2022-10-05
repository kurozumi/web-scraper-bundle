<?php

namespace Kurozumi\WebScraperBundle\DependencyInjection;

use Kurozumi\WebScraperBundle\Service\ScraperInterface;
use Kurozumi\WebScraperBundle\DependencyInjection\Compiler\ScraperPass;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class WebScraperExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $container->registerForAutoconfiguration(ScraperInterface::class)
            ->addTag(ScraperPass::TAG);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
    }
}