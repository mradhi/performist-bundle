<?php
/*
 * This file is part of the PerformistBundle package.
 *
 * (c) Radhi GUENNICHI <radhi@guennichi.com> (https://www.guennichi.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Guennichi\PerformistBundle\Tests;


use Guennichi\PerformistBundle\GuennichiPerformistBundle;
use Guennichi\PerformistBundle\Tests\Mock\ActionHandler;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\Kernel;

class TestKernel extends Kernel
{
    public function registerBundles(): iterable
    {
        return [
            new GuennichiPerformistBundle()
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(function (ContainerBuilder $container) {
            $handlerDef = new Definition(ActionHandler::class);
            $handlerDef->addTag('guennichi_performist.handler');

            $middlewareDef = new Definition('Guennichi\PerformistBundle\Tests\Mock\Middleware');
            $middlewareDef->addTag('guennichi_performist.middleware');

            $container->setDefinition('test.action_handler', $handlerDef);
            $container->setDefinition('test.middleware', $middlewareDef);

            $container->loadFromExtension('guennichi_performist', [
                'middlewares' => [
                    'test.middleware'
                ]
            ]);
        });
    }
}
