<?php
/*
 * This file is part of the PerformistBundle package.
 *
 * (c) Radhi GUENNICHI <hello@guennichi.com> (https://www.guennichi.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Guennichi\PerformistBundle;


use Guennichi\Performist\HandlerInterface;

abstract class AbstractHandler implements HandlerInterface
{
    /**
     * Additional handler middleware list, used to be able to inject middleware classes.
     *
     * @var string[]
     */
    public static array $middlewares = [];
}
