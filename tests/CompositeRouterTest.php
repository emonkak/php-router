<?php

namespace Emonkak\Router\Tests;

use Emonkak\Router\RouterInterface;
use Emonkak\Router\CompositeRouter;

class CompositeRouterTest extends \PHPUnit_Framework_TestCase
{
    public function testMatch()
    {
        $router1 = $this->createMock(RouterInterface::class);
        $router1
            ->expects($this->once())
            ->method('match')
            ->with($this->identicalTo('/qux'))
            ->will($this->returnArgument(0));

        $router2 = $this->createMock(RouterInterface::class);
        $router2
            ->expects($this->once())
            ->method('match')
            ->with($this->identicalTo('/quux'))
            ->will($this->returnArgument(0));

        $router3 = $this->createMock(RouterInterface::class);
        $router3
            ->expects($this->once())
            ->method('match')
            ->with($this->identicalTo('/baz/corge'))
            ->will($this->returnArgument(0));

        $compositeRouter = new CompositeRouter([
            '/foo/' => $router1,
            '/bar/' => $router2,
            '/' => $router3,
        ]);

        $this->assertSame('/qux', $compositeRouter->match('/foo/qux'));
        $this->assertSame('/quux', $compositeRouter->match('/bar/quux'));
        $this->assertSame('/baz/corge', $compositeRouter->match('/baz/corge'));
    }

    public function testMatchFailure()
    {
        $router1 = $this->createMock(RouterInterface::class);
        $router1
            ->expects($this->once())
            ->method('match')
            ->with($this->identicalTo('/qux'))
            ->will($this->returnArgument(0));

        $router2 = $this->createMock(RouterInterface::class);
        $router2
            ->expects($this->once())
            ->method('match')
            ->with($this->identicalTo('/quux'))
            ->will($this->returnArgument(0));

        $router3 = $this->createMock(RouterInterface::class);
        $router3
            ->expects($this->at(0))
            ->method('match')
            ->with($this->identicalTo('/baz/corge'))
            ->will($this->returnArgument(0));
        $router3
            ->expects($this->at(1))
            ->method('match')
            ->with($this->identicalTo('/'))
            ->will($this->returnArgument(0));

        $compositeRouter = new CompositeRouter([
            '/foo/' => $router1,
            '/bar/' => $router2,
            '/' => $router3,
        ]);

        $this->assertSame('/qux', $compositeRouter->match('/foo/qux'));
        $this->assertSame('/quux', $compositeRouter->match('/bar/quux'));
        $this->assertSame('/baz/corge', $compositeRouter->match('/baz/corge'));
        $this->assertSame('/', $compositeRouter->match('/'));
        $this->assertNull((new CompositeRouter([]))->match('/'));
    }
}
