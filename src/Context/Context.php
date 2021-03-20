<?php
/*
 * This file is part of the PerformistBundle package.
 *
 * (c) Radhi GUENNICHI <radhi@guennichi.com> (https://www.guennichi.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Guennichi\PerformistBundle\Context;


use Symfony\Contracts\EventDispatcher\Event;

class Context
{
    /**
     * @var mixed
     */
    private $rootAction;

    /**
     * Deferred events to be executed after performing/handling the action.
     *
     * @var Event[]
     */
    private array $deferredEvents = [];

    /**
     * Custom attributes
     *
     * @var array
     */
    private array $attributes = [];

    public function __construct($rootAction)
    {
        $this->rootAction = $rootAction;
    }

    /**
     * @param $action
     *
     * @return bool
     */
    public function isWrappedBy($action): bool
    {
        return $action === $this->rootAction;
    }

    /**
     * Dispatch an event AFTER the whole actions process in this context is successfully performed/handled.
     * Sequence: preMiddlewares -> handlers -> postMiddlewares -> deferredEventsSubscribers
     *
     * @param Event $event
     */
    public function deferEvent(Event $event): void
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
     * Set custom attribute value.
     *
     * @param string $name
     * @param $value
     */
    public function setAttribute(string $name, $value): void
    {
        $this->attributes[$name] = $value;
    }

    /**
     * Get custom attribute value.
     *
     * @param string $name
     * @param null $default
     *
     * @return mixed|null
     */
    public function getAttribute(string $name, $default = null)
    {
        if ($this->hasAttribute($name)) {
            return $this->attributes[$name];
        }

        return $default;
    }

    /**
     * Check if a given attribute exists.
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasAttribute(string $name): bool
    {
        return array_key_exists($name, $this->attributes);
    }
}
