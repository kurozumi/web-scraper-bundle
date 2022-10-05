<?php

namespace Kurozumi\WebScraperBundle\DependencyInjection;

use Kurozumi\WebScraperBundle\Service\ScraperInterface;
use Kurozumi\WebScraperBundle\DependencyInjection\Compiler\ScraperRegistrationPass;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class WebScraperExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
        $loader->load('scrapers.yaml');

        $container->registerForAutoconfiguration(ScraperInterface::class)
            ->addTag(ScraperRegistrationPass::TAG);
    }
}