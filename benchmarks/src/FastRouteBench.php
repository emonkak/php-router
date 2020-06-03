<?php

declare(strict_types=1);

namespace Emonkak\Router\Benchmarks;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class FastRouteBench
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
        assert($this->buildRouter() instanceof Dispatcher);
    }

    /**
     * @BeforeMethods({"setUp"})
     * @Groups({"match"})
     */
    public function benchMatchFirstRule()
    {
        assert($this->router->dispatch('GET', '/') == [Dispatcher::FOUND, 0, []]);
    }

    /**
     * @BeforeMethods({"setUp"})
     * @Groups({"match"})
     */
    public function benchMatchLastRule()
    {
        assert($this->router->dispatch('GET', '/baz/123/foobar/456') == [Dispatcher::FOUND, 12, ['first' => '123', 'second' => '456']]);
    }

    /**
     * @BeforeMethods({"setUp"})
     * @Groups({"match"})
     */
    public function benchNotMatch()
    {
        assert($this->router->dispatch('GET', '/foo/123/qux/456/') === [Dispatcher::NOT_FOUND]);
    }

    protected function buildRouter()
    {
        return simpleDispatcher(function(RouteCollector $r) {
            $r->addRoute('GET', '/', 0);
            $r->addRoute('GET', '/foo/', 1);
            $r->addRoute('GET', '/bar/', 2);
            $r->addRoute('GET', '/baz/', 3);
            $r->addRoute('GET', '/foo/{first}', 4);
            $r->addRoute('GET', '/bar/{first}', 5);
            $r->addRoute('GET', '/baz/{first}', 6);
            $r->addRoute('GET', '/foo/{first}/qux', 7);
            $r->addRoute('GET', '/bar/{first}/quux', 8);
            $r->addRoute('GET', '/baz/{first}/foobar', 9);
            $r->addRoute('GET', '/foo/{first}/qux/{second}', 10);
            $r->addRoute('GET', '/bar/{first}/quux/{second}', 11);
            $r->addRoute('GET', '/baz/{first}/foobar/{second}', 12);
        });
    }
}
