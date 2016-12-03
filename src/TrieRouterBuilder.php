<?php

namespace Emonkak\Router;

class TrieRouterBuilder extends AbstractRouterBuilder
{
    /**
     * {@inheritDoc}
     */
    public function build()
    {
        $router = new TrieRouter();

        foreach ($this->routes as $path => $route) {
            $router->route($path, $route);
        }

        return $router;
    }
}
