<?php

namespace Emonkak\Router\Tests;

use Emonkak\Router\RegexpRouter;

/**
 * @covers Emonkak\Router\RegexpRouter
 */
class RegexpRouterTest extends AbstractRoutableRouterTest
{
    /**
     * @expectedException OverflowException
     */
    public function testMatchThrowsOverflowException()
    {
        $path = str_repeat('/foo', 10000);

        $router = (new RegexpRouter())
            ->route($path, 'bar');

        $router->match($path);
    }

    public function testChunkedMatch()
    {
        $router = (new RegexpRouter());

        for ($i = 0; $i < 100; $i++) {
            $router->route(str_repeat('/foo', $i), $i);
        }

        $match = $router->match('/foo/foo/foo/foo/foo/foo/foo/foo/foo/foo');
        $this->assertEquals([10, []], $match);

        $match = $router->match('/foo/foo/foo/foo/foo/foo/foo/foo/foo/foo/');
        $this->assertNull($match);
    }

    public function prepareRouter()
    {
        return new RegexpRouter();
    }
}
