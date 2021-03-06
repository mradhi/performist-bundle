<?php
/*
 * This file is part of the PerformistBundle package.
 *
 * (c) Radhi GUENNICHI <radhi@guennichi.com> (https://www.guennichi.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Guennichi\PerformistBundle;


use Guennichi\Performist\HandlerInterface;
use Symfony\Contracts\EventDispatcher\Event;

abstract class AbstractHandler implements HandlerInterface
{
    /**
     * Additional handler middleware list, used to be able to inject middleware classes.
     *
     * @var string[]
     */
    public static array $middlewares = [];

    /**
     * @var Event[]
     */
    private array $postDispatchEvents = [];

    /**
     * Dispatch an event after the action is successfully handled.
     * Sequence: preMiddlewares -> Handle -> postMiddlewares -> postDispatchEventsSubscribers
     *
     * @param Event $event
     */
    protected function dispatchAfterHandled(Event $event): void
    {
        $this->postDispatchEvents[] = $event;
    }

    /**
     * @return Event[]
     */
    public function getPostDispatchEvents(): array
    {
        return $this->postDispatchEvents;
    }
}
