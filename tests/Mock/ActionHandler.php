<?php
/*
 * This file is part of the PerformistBundle package.
 *
 * (c) Radhi GUENNICHI <radhi@guennichi.com> (https://www.guennichi.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Guennichi\PerformistBundle\Tests\Mock;


use Guennichi\PerformistBundle\AbstractHandler;
use Guennichi\PerformistBundle\Context\Context;

class ActionHandler extends AbstractHandler
{
    public function __invoke(Action $action, Context $context): Action
    {
        $action->runs[] = 'core';

        $context->deferEvent(new CustomEvent($action));

        return $action;
    }
}
