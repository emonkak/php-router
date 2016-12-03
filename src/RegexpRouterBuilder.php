<?php

namespace Emonkak\Router;

class RegexpRouterBuilder extends AbstractRouterBuilder
{
    /**
     * {@inheritDoc}
     */
    public function build()
    {
        $router = new RegexpRouter();

        foreach ($this->routes as $path => $handler) {
            $router->route($path, $handler);
        }

        return $router;
    }
}
