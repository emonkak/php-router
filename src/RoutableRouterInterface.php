<?php

namespace Emonkak\Router;

interface RoutableRouterInterface extends RouterInterface
{
    /**
     * @param string $path
     * @param mixed  $handler
     * @return $this
     */
    public function route($path, $handler);
}
