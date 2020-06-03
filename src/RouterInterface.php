<?php

declare(strict_types=1);

namespace Emonkak\Router;

/**
 * Represents the matching of a path to routes.
 *
 * @template THandler
 * @template TParam
 */
interface RouterInterface
{
    /**
     * @return ?array{0:THandler,1:TParam[]} The found handler and parameters pair
     */
    public function match(string $path): ?array;
}
