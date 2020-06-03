<?php

declare(strict_types=1);

namespace Emonkak\Router;

/**
 * @template THandler
 * @template TParam
 */
abstract class AbstractRouterBuilder
{
    /**
     * @var array<string,array<string,THandler>>
     */
    protected $routes = [];

    /**
     * @param THandler $handler
     * @return $this
     */
    public function get(string $path, $handler): self
    {
        return $this->route('GET', $path, $handler);
    }

    /**
     * @param THandler $handler
     * @return $this
     */
    public function post(string $path, $handler): self
    {
        return $this->route('POST', $path, $handler);
    }

    /**
     * @param THandler $handler
     * @return $this
     */
    public function delete(string $path, $handler): self
    {
        return $this->route('DELETE', $path, $handler);
    }

    /**
     * @param THandler $handler
     * @return $this
     */
    public function put(string $path, $handler): self
    {
        return $this->route('PUT', $path, $handler);
    }

    /**
     * @param THandler $handler
     * @return $this
     */
    public function patch(string $path, $handler): self
    {
        return $this->route('PATCH', $path, $handler);
    }

    /**
     * @param THandler $handler
     * @return $this
     */
    public function route(string $method, string $path, $handler): self
    {
        if (!isset($this->routes[$path])) {
            $this->routes[$path] = [];
        }

        $this->routes[$path][$method] = $handler;

        return $this;
    }

    /**
     * @return RouterInterface<THandler,TParam>
     */
    public function build(): RouterInterface
    {
        $router = $this->prepareRouter();

        foreach ($this->routes as $path => $route) {
            $router->addRoute($path, $route);
        }

        return $router;
    }

    /**
     * @return RoutableRouterInterface<THandler,TParam>
     */
    abstract protected function prepareRouter(): RoutableRouterInterface;
}
