<?php

namespace Emonkak\Router\Benchmarks;

use Emonkak\Router\RouterInterface;

abstract class AbstractRoutableRouterBench
{
    private $router;

    public function setUp()
    {
        $this->router = $this->buildRouter();
    }

    /**
     * @BeforeMethods({"setUp"})
     * @Groups({"build"})
     */
    public function benchBuild()
    {
        assert($this->buildRouter() instanceof RouterInterface);
    }

    /**
     * @BeforeMethods({"setUp"})
     * @Groups({"match"})
     */
    public function benchMatchFirstRule()
    {
        assert($this->router->match('/') == [0, []]);
    }

    /**
     * @BeforeMethods({"setUp"})
     * @Groups({"match"})
     */
    public function benchMatchLastRule()
    {
        assert($this->router->match('/baz/123/foobar/456') == [12, ['first' => '123', 'second' => '456']]);
    }

    /**
     * @BeforeMethods({"setUp"})
     * @Groups({"match"})
     */
    public function benchNotMatch()
    {
        assert($this->router->match('/foo/123/qux/456/') === null);
    }

    protected function buildRouter()
    {
        return $this->prepareRouter()
            ->route('/', 0)
            ->route('/foo/', 1)
            ->route('/bar/', 2)
            ->route('/baz/', 3)
            ->route('/foo/:first', 4)
            ->route('/bar/:first', 5)
            ->route('/baz/:first', 6)
            ->route('/foo/:first/qux', 7)
            ->route('/bar/:first/quux', 8)
            ->route('/baz/:first/foobar', 9)
            ->route('/foo/:first/qux/:second', 10)
            ->route('/bar/:first/quux/:second', 11)
            ->route('/baz/:first/foobar/:second', 12);
    }

    abstract protected function prepareRouter();
}
