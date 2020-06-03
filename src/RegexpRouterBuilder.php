<?php

declare(strict_types=1);

namespace Emonkak\Router;

/**
 * @template THandler
 * @extends AbstractRouterBuilder<THandler,string>
 */
class RegexpRouterBuilder extends AbstractRouterBuilder
{
    /**
     * @return RegexpRouter<THandler>
     */
    public function prepareRouter(): RoutableRouterInterface
    {
        return new RegexpRouter();
    }
}
