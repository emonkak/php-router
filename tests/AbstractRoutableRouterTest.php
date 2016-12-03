<?php

namespace Emonkak\Router\Tests;

abstract class AbstractRoutableRouterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerMatch
     */
    public function testMatch($path, $expectedMetadata, array $expectedParams)
    {
        $router = $this->prepareRouter()
            ->route('/', 0)
            ->route('/foo', 1)
            ->route('/bar', 2)
            ->route('/baz', 3)
            ->route('/foo/:first', 4)
            ->route('/bar/:first', 5)
            ->route('/baz/:first', 6)
            ->route('/foo/:first/qux', 7)
            ->route('/bar/:first/quux', 8)
            ->route('/baz/:first/foobar', 9)
            ->route('/foo/:first/qux/:second', 10)
            ->route('/bar/:first/quux/:second', 11)
            ->route('/baz/:first/foobar/:second', 12);

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
    public function testNotMatch($path)
    {
        $router = $this->prepareRouter()
            ->route('/', 0)
            ->route('/foo', 1)
            ->route('/bar', 2)
            ->route('/baz', 3)
            ->route('/foo/:first', 4)
            ->route('/bar/:first', 5)
            ->route('/baz/:first', 6)
            ->route('/foo/:first/qux', 7)
            ->route('/bar/:first/quux', 8)
            ->route('/baz/:first/foobar', 9)
            ->route('/foo/:first/qux/:second', 10)
            ->route('/bar/:first/quux/:second', 11)
            ->route('/baz/:first/foobar/:second', 12);

        $match = $router->match($path);
        $this->assertNull($match);
    }

    public function providerNotMatch()
    {
        return [
            [''],
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
            $router->route(str_repeat('/foo', $i), $i);
        }

        $match = $router->match('/foo/foo/foo/foo/foo/foo/foo/foo/foo/foo');
        $this->assertEquals([10, []], $match);

        $match = $router->match('/foo/foo/foo/foo/foo/foo/foo/foo/foo/foo/');
        $this->assertNull($match);
    }

    abstract protected function prepareRouter();
}
