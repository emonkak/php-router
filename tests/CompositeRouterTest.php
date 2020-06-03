<?php

namespace Emonkak\Router\Tests;

use Emonkak\Router\CompositeRouter;
use Emonkak\Router\RouterInterface;
use PHPUnit\Framework\TestCase;

class CompositeRouterTest extends TestCase
{
    public function testMatch()
    {
        $router1 = $this->createMock(RouterInterface::class);
        $router1
            ->expects($this->once())
            ->method('match')
            ->with($this->identicalTo('/qux'))
            ->willReturn(['/qux', []]);

        $router2 = $this->createMock(RouterInterface::class);
        $router2
            ->expects($this->once())
            ->method('match')
            ->with($this->identicalTo('/quux'))
            ->willReturn(['/quux', []]);

        $router3 = $this->createMock(RouterInterface::class);
        $router3
            ->expects($this->once())
            ->method('match')
            ->with($this->identicalTo('/baz/corge'))
            ->willReturn(['/baz/corge', []]);

        $compositeRouter = new CompositeRouter([
            '/foo/' => $router1,
            '/bar/' => $router2,
            '/' => $router3,
        ]);

        $this->assertSame(['/qux', []], $compositeRouter->match('/foo/qux'));
        $this->assertSame(['/quux', []], $compositeRouter->match('/bar/quux'));
        $this->assertSame(['/baz/corge', []], $compositeRouter->match('/baz/corge'));
    }

    public function testMatchFailure()
    {
        $router1 = $this->createMock(RouterInterface::class);
        $router1
            ->expects($this->once())
            ->method('match')
            ->with($this->identicalTo('/qux'))
            ->willReturn(['/qux', []]);

        $router2 = $this->createMock(RouterInterface::class);
        $router2
            ->expects($this->once())
            ->method('match')
            ->with($this->identicalTo('/quux'))
            ->willReturn(['/quux', []]);

        $router3 = $this->createMock(RouterInterface::class);
        $router3
            ->expects($this->at(0))
            ->method('match')
            ->with($this->identicalTo('/baz/corge'))
            ->willReturn(['/baz/corge', []]);
        $router3
            ->expects($this->at(1))
            ->method('match')
            ->with($this->identicalTo('/'))
            ->willReturn(['/', []]);

        $compositeRouter = new CompositeRouter([
            '/foo/' => $router1,
            '/bar/' => $router2,
            '/' => $router3,
        ]);

        $this->assertSame(['/qux', []], $compositeRouter->match('/foo/qux'));
        $this->assertSame(['/quux', []], $compositeRouter->match('/bar/quux'));
        $this->assertSame(['/baz/corge', []], $compositeRouter->match('/baz/corge'));
        $this->assertSame(['/', []], $compositeRouter->match('/'));
        $this->assertNull((new CompositeRouter([]))->match('/'));
    }
}
