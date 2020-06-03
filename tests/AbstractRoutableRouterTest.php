<?php

namespace Emonkak\Router\Tests;

use PHPUnit\Framework\TestCase;

abstract class AbstractRoutableRouterTest extends TestCase
{
    /**
     * @dataProvider providerMatch
     */
    public function testMatch($path, $expectedMetadata, array $expectedParams)
    {
        $router = $this->prepareRouter();
        $router->addRoute('/', 0);
        $router->addRoute('/foo', 1);
        $router->addRoute('/bar', 2);
        $router->addRoute('/baz', 3);
        $router->addRoute('/foo/:first', 4);
        $router->addRoute('/bar/:first', 5);
        $router->addRoute('/baz/:first', 6);
        $router->addRoute('/foo/:first/qux', 7);
        $router->addRoute('/bar/:first/quux', 8);
        $router->addRoute('/baz/:first/foobar', 9);
        $router->addRoute('/foo/:first/qux/:second', 10);
        $router->addRoute('/bar/:first/quux/:second', 11);
        $router->addRoute('/baz/:first/foobar/:second', 12);

        $match = $router->match($path);
        $this->assertEquals([$expectedMetadata, $expectedParams], $match);
    }

    /**
     * @dataProvider providerMatch
     */
    public function testMatchEmpty($path)
    {
        $router = $this->prepareRouter();

        $match = $router->match($path);
        $this->assertNull($match);
    }

    public function providerMatch()
    {
        return [
            ['/', 0, []],
            ['/foo', 1, []],
            ['/bar', 2, []],
            ['/baz', 3, []],
            ['/foo/123', 4, ['first' => 123]],
            ['/bar/123', 5, ['first' => 123]],
            ['/baz/123', 6, ['first' => 123]],
            ['/foo/123/qux', 7, ['first' => 123]],
            ['/bar/123/quux', 8, ['first' => 123]],
            ['/baz/123/foobar', 9, ['first' => 123]],
            ['/foo/123/qux/456', 10, ['first' => 123, 'second' => 456]],
            ['/bar/123/quux/456', 11, ['first' => 123, 'second' => 456]],
            ['/baz/123/foobar/456', 12, ['first' => 123, 'second' => 456]],
        ];
    }

    /**
     * @dataProvider providerNotMatch
     */
    public function testMatchFailure($path)
    {
        $router = $this->prepareRouter();
        $router->addRoute('/', 0);
        $router->addRoute('/foo', 1);
        $router->addRoute('/bar', 2);
        $router->addRoute('/baz', 3);
        $router->addRoute('/foo/:first', 4);
        $router->addRoute('/bar/:first', 5);
        $router->addRoute('/baz/:first', 6);
        $router->addRoute('/foo/:first/qux', 7);
        $router->addRoute('/bar/:first/quux', 8);
        $router->addRoute('/baz/:first/foobar', 9);
        $router->addRoute('/foo/:first/qux/:second', 10);
        $router->addRoute('/bar/:first/quux/:second', 11);
        $router->addRoute('/baz/:first/foobar/:second', 12);

        $match = $router->match($path);
        $this->assertNull($match);
    }

    public function providerNotMatch()
    {
        return [
            [''],
            ['//'],
            ['/foo/'],
            ['/bar/'],
            ['/baz/'],
            ['/foo/123/'],
            ['/bar/123/'],
            ['/baz/123/'],
        ];
    }

    public function testChunkedMatch()
    {
        $router = $this->prepareRouter();

        for ($i = 0; $i < 100; $i++) {
            $router->addRoute(str_repeat('/foo', $i), $i);
        }

        $match = $router->match('/foo/foo/foo/foo/foo/foo/foo/foo/foo/foo');
        $this->assertEquals([10, []], $match);

        $match = $router->match('/foo/foo/foo/foo/foo/foo/foo/foo/foo/foo/');
        $this->assertNull($match);
    }

    abstract protected function prepareRouter();
}
