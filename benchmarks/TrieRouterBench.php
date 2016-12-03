<?php

namespace Emonkak\Router\Benchmarks;

use Emonkak\Router\TrieRouter;

class TrieRouterBench extends AbstractRoutableRouterBench
{
    protected function prepareRouter()
    {
        return new TrieRouter();
    }
}
