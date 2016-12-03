<?php

namespace Emonkak\Router;

interface RoutableRouterInterface extends RouterInterface
{
    /**
     * @param string $path
     * @param mixed  $metadata
     * @return $this
     */
    public function route($path, $metadata);
}
