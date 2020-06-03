<?php

declare(strict_types=1);

namespace Emonkak\Router;

/**
 * @template THandler
 * @template TParam
 * @extends RoutableInterface<THandler,TParam>
 */
interface RoutableRouterInterface extends RouterInterface
{
    /**
     * @param THandler $handler
     */
    public function addRoute(string $path, $handler): void;
}
