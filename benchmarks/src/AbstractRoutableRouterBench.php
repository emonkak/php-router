<?php

declare(strict_types=1);

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
        $router = $this->prepareRouter();
        $router->addroute('/', 0);
        $router->addroute('/foo/', 1);
        $router->addroute('/bar/', 2);
        $router->addroute('/baz/', 3);
        $router->addroute('/foo/:first', 4);
        $router->addroute('/bar/:first', 5);
        $router->addroute('/baz/:first', 6);
        $router->addroute('/foo/:first/qux', 7);
        $router->addroute('/bar/:first/quux', 8);
        $router->addroute('/baz/:first/foobar', 9);
        $router->addroute('/foo/:first/qux/:second', 10);
        $router->addroute('/bar/:first/quux/:second', 11);
        $router->addroute('/baz/:first/foobar/:second', 12);
    }

    abstract protected function prepareRouter();
}
