<?php

declare(strict_types=1);

namespace Emonkak\Router;

/**
 * @template THandler
 * @extends AbstractRouterBuilder<THandler,string>
 */
class TrieRouterBuilder extends AbstractRouterBuilder
{
    /**
     * @return TrieRouter<THandler>
     */
    protected function prepareRouter(): RoutableRouterInterface
    {
        return new TrieRouter();
    }
}
