<?php

namespace Emonkak\Router;

class CompositeRouter implements RouterInterface
{
    /**
     * @var array
     */
    private $routers;

    /**
     * @param array $routers
     */
    public function __construct(array $routers)
    {
        $this->routers = $routers;
    }

    /**
     * {@inheritDoc}
     */
    public function match($path)
    {
        foreach ($this->routers as $prefix => $router) {
            if (strpos($path, $prefix) === 0) {
                $tailPath = substr($path, strlen(rtrim($prefix, '/')));
                return $router->match($tailPath);
            }
        }
        return null;
    }
}
