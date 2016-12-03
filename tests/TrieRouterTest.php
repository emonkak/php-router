<?php

namespace Emonkak\Router\Tests;

use Emonkak\Router\TrieRouter;

/**
 * @covers Emonkak\Router\TrieRouter
 */
class TrieRouterTest extends AbstractRoutableRouterTest
{
    public function prepareRouter()
    {
        return new TrieRouter();
    }
}
