<?php

namespace Emonkak\Router\Benchmarks;

use Emonkak\Router\RegexpRouter;

class RegexpRouterBench extends AbstractRoutableRouterBench
{
    protected function prepareRouter()
    {
        return new RegexpRouter();
    }
}
