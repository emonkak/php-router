<?php

namespace Emonkak\Router;

class RegexpRouterBuilder extends AbstractRouterBuilder
{
    /**
     * {@inheritDoc}
     */
    public function prepareRouter()
    {
        return new RegexpRouter();
    }
}
