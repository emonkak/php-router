<?php

namespace Emonkak\Router;

abstract class AbstractRouterBuilder
{
    /**
     * @var array
     */
    protected $routes = [];

    /**
     * @param string       $method
     * @param string       $path
     * @param string|array $handler
     * @return $this
     */
    public function route($method, $path, $handler)
    {
        if (!isset($this->routes[$path])) {
            $this->routes[$path] = [];
        }

        $this->routes[$path][$method] = $handler;

        return $this;
    }

    /**
     * @param string       $path
     * @param string|array $handler
     * @return $this
     */
    public function get($path, $handler)
    {
        return $this->route('GET', $path, $handler);
    }

    /**
     * @param string       $path
     * @param string|array $handler
     * @return $this
     */
    public function post($path, $handler)
    {
        return $this->route('POST', $path, $handler);
    }

    /**
     * @param string       $path
     * @param string|array $handler
     * @return $this
     */
    public function delete($path, $handler)
    {
        return $this->route('DELETE', $path, $handler);
    }

    /**
     * @param string       $path
     * @param string|array $handler
     * @return $this
     */
    public function put($path, $handler)
    {
        return $this->route('PUT', $path, $handler);
    }

    /**
     * @param string       $path
     * @param string|array $handler
     * @return $this
     */
    public function patch($path, $handler)
    {
        return $this->route('PATCH', $path, $handler);
    }

    /**
     * @return RouterInterface
     */
    abstract public function build();
}