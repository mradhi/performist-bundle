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


use Guennichi\PerformistBundle\DependencyInjection\GuennichiPerformistExtension;
use Guennichi\PerformistBundle\GuennichiPerformistBundle;
use PHPUnit\Framework\TestCase;

class GuennichiPerformistBundleTest extends TestCase
{
    public function testShouldReturnNewContainerExtension(): void
    {
        $testBundle = new GuennichiPerformistBundle();
        $result = $testBundle->getContainerExtension();

        $this->assertInstanceOf(GuennichiPerformistExtension::class, $result);
    }
}
