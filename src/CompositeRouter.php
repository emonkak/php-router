<?php

declare(strict_types=1);

namespace Emonkak\Router;

/**
 * @template THandler
 * @template TParam
 */
class CompositeRouter implements RouterInterface
{
    /**
     * @var RouterInterface<THandler,TParam>[]
     */
    private $routers;

    /**
     * @param RouterInterface<THandler,TParam>[] $routers
     */
    public function __construct(array $routers)
    {
        $this->routers = $routers;
    }

    /**
     * {@inheritdoc}
     */
    public function match(string $path): ?array
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
