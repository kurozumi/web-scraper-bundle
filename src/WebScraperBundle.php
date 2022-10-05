<?php
declare(strict_types=1);

namespace Kurozumi\WebScraperBundle;

use Kurozumi\WebCrawlerBundle\Service\ScraperInterface;
use Kurozumi\WebScraperBundle\DependencyInjection\Compiler\ScraperPass;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class WebScraperBundle extends AbstractBundle
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

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $loader = new YamlFileLoader($builder, new FileLocator(__DIR__ . '/Resources/config'));
        $loader->load('services.yaml');
    }
}