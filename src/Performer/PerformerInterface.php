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


use Guennichi\Performist\Exception\PerformistException;
use Guennichi\PerformistBundle\Context\Context;

interface PerformerInterface
{
    /**
     * @param mixed $action
     * @param string[] $middlewares additional middlewares class names.
     * @param Context|null $context
     *
     * @return mixed
     *
     * @throws PerformistException
     */
    public function perform($action, array $middlewares = [], Context $context = null);
}
