<?php
/*
 * This file is part of the PerformistBundle package.
 *
 * (c) Radhi GUENNICHI <hello@guennichi.com> (https://www.guennichi.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Guennichi\PerformistBundle\Tests\Mock;


use Closure;
use Guennichi\Performist\MiddlewareInterface;

class Middleware implements MiddlewareInterface
{
    /**
     * @param object|Action $action
     * @param Closure $next
     *
     * @return mixed|void
     */
    public function handle($action, Closure $next)
    {
        $action->runs[] = 'middleware_before';

        $result = $next($action);

        $action->runs[] = 'middleware_after';

        return $result;
    }
}
