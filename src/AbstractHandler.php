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
     * Deferred events to be executed after performing/handling the action.
     *
     * @var Event[]
     */
    private array $deferredEvents = [];

    /**
     * Dispatch an event after the action is successfully performed/handled.
     * Sequence: preMiddlewares -> Handle -> postMiddlewares -> deferredEventsSubscribers
     *
     * @param Event $event
     */
    protected function defer(Event $event): void
    {
        $this->deferredEvents[] = $event;
    }

    /**
     * Get deferred events.
     *
     * @internal
     *
     * @return Event[]
     */
    public function getDeferredEvents(): array
    {
        return $this->deferredEvents;
    }

    /**
     * Clone the handler
     */
    public function __clone()
    {
        // Cleanup the deferred events list
        // Useful for same actions with same handler reference
        // inside a nested perform() calls.
        $this->deferredEvents = [];
    }
}
