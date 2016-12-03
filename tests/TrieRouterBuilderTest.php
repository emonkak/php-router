<?php

namespace Emonkak\Router\Tests;

use Emonkak\Router\TrieRouterBuilder;

/**
 * @covers Emonkak\Router\TrieRouterBuilder
 * @covers Emonkak\Router\AbstractRouterBuilder
 */
class TrieRouterBuilderTest extends AbstractRouterBuilderTest
{
    public function prepareBuilder()
    {
        return new TrieRouterBuilder();
    }
}
