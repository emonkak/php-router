<?php

namespace Emonkak\Router;

interface RoutableRouterInterface extends RouterInterface
{
    /**
     * @param string $path
     * @param mixed  $data
     * @return $this
     */
    public function route($path, $data);
}
