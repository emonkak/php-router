<?php

declare(strict_types=1);

namespace Emonkak\Router\Benchmarks;

use Emonkak\Router\TrieRouter;

class TrieRouterBench extends AbstractRoutableRouterBench
{
    protected function prepareRouter()
    {
        return new TrieRouter();
    }
}
