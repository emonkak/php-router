<?php

namespace Emonkak\Router;

/**
 * Represents the matching of a path to routes.
 */
interface RouterInterface
{
    /**
     * @param string $path
     * @return array|null The found metadata and parameters pair
     */
    public function match($path);
}
