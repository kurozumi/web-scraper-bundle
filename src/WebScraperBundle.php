<?php
declare(strict_types=1);

namespace Kurozumi\WebScraperBundle;

use Kurozumi\WebScraperBundle\Service\ScraperInterface;
use Kurozumi\WebScraperBundle\DependencyInjection\Compiler\ScraperPass;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class WebScraperBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ScraperPass());
    }
}