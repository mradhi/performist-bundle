<?php
/*
 * This file is part of the PerformistBundle package.
 *
 * (c) Radhi GUENNICHI <radhi@guennichi.com> (https://www.guennichi.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Guennichi\PerformistBundle\DependencyInjection;


use Guennichi\Performist\HandlerInterface;
use Guennichi\Performist\MiddlewareInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class GuennichiPerformistExtension extends Extension
{
    /**
     * @inheritDoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $defaultMiddlewares = $config['middlewares'];
        $container->getDefinition('guennichi_performist.performer')
            ->replaceArgument(4, $defaultMiddlewares);

        $container->registerForAutoconfiguration(HandlerInterface::class)
            ->addTag('guennichi_performist.handler');
        $container->registerForAutoconfiguration(MiddlewareInterface::class)
            ->addTag('guennichi_performist.middleware');
    }
}
