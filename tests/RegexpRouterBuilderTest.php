<?php

namespace Emonkak\Router\Tests;

use Emonkak\Router\RegexpRouterBuilder;

/**
 * @covers Emonkak\Router\RegexpRouterBuilder
 * @covers Emonkak\Router\AbstractRouterBuilder
 */
class RegexpRouterBuilderTest extends AbstractRouterBuilderTest
{
    public function prepareBuilder()
    {
        return new RegexpRouterBuilder();
    }
}
