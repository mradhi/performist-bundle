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


use Guennichi\PerformistBundle\Tests\Mock\Action;
use PHPUnit\Framework\TestCase;

class FunctionalTest extends TestCase
{
    public function testServicesAreUsable(): void
    {
        $kernel = new TestKernel('test', true);
        $kernel->boot();
        $container = $kernel->getContainer();

        $this->assertTrue($container->has('guennichi_performist.performer'));

        $performer = $container->get('guennichi_performist.performer');

        $result = $performer->perform(new Action());
        $this->assertSame(['middleware_before', 'core', 'middleware_after', 'custom_event'], $result->runs);
    }
}
