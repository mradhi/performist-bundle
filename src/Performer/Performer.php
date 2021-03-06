<?php
/*
 * This file is part of the PerformistBundle package.
 *
 * (c) Radhi GUENNICHI <radhi@guennichi.com> (https://www.guennichi.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Guennichi\PerformistBundle\Performer;


use Guennichi\Performist\Performer as BasePerformer;
use Guennichi\Performist\Registry;
use Guennichi\PerformistBundle\AbstractHandler;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\DependencyInjection\ServiceLocator;

/**
 * Performer decorator
 */
class Performer implements PerformerInterface
{
    protected BasePerformer $basePerformer;

    protected Registry $registry;

    protected ServiceLocator $middlewareServiceLocator;

    protected EventDispatcherInterface $eventDispatcher;

    protected array $defaultMiddlewares;

    /**
     * @param BasePerformer $basePerformer
     * @param Registry $registry
     * @param EventDispatcherInterface $eventDispatcher
     * @param ServiceLocator $middlewareServiceLocator
     * @param string[] $defaultMiddlewares
     */
    public function __construct(
        BasePerformer $basePerformer,
        Registry $registry,
        EventDispatcherInterface $eventDispatcher,
        ServiceLocator $middlewareServiceLocator,
        array $defaultMiddlewares = [])
    {
        $this->basePerformer = $basePerformer;
        $this->registry = $registry;
        $this->eventDispatcher = $eventDispatcher;
        $this->middlewareServiceLocator = $middlewareServiceLocator;
        $this->defaultMiddlewares = $defaultMiddlewares;
    }

    /**
     * @inheritDoc
     */
    public function perform($action, array $middlewares = [])
    {
        $handler = $this->registry->get(get_class($action));

        // Merge with the default middlewares
        // Priority to the default ones.
        $middlewares = array_merge($this->defaultMiddlewares, $middlewares);
        if ($handler instanceof AbstractHandler && !empty($handler::$middlewares)) {
            $middlewares = array_merge($middlewares, $handler::$middlewares);
        }
        // Fetch middleware services instances from classnames
        $middlewares = array_map(function (string $middlewareId) {
            return $this->middlewareServiceLocator->get($middlewareId);
        }, $middlewares);

        $result = $this->basePerformer->perform($action, $middlewares);

        if ($handler instanceof AbstractHandler) {
            foreach ($handler->getDeferredEvents() as $event) {
                $this->eventDispatcher->dispatch($event);
            }
        }


        return $result;
    }
}
