<?php

namespace Emonkak\Router;

class TrieRouterBuilder extends AbstractRouterBuilder
{
    /**
     * {@inheritDoc}
     */
    public function prepareRouter()
    {
        return new TrieRouter();
    }
}
