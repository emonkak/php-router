<?php

namespace Emonkak\Router\Tests;

use PHPUnit\Framework\TestCase;

abstract class AbstractRouterBuilderTest extends TestCase
{
    public function testBuild()
    {
        $router = $this->prepareBuilder()
            ->get('/users', 'IndexUser')
            ->post('/users/:user_id', 'StoreUser')
            ->patch('/users/:user_id', 'UpdateUser')
            ->put('/users/:user_id', 'UpdateUser')
            ->delete('/users/:user_id', 'DestroyUser')
            ->build();

        $this->assertEquals([
            ['GET' => 'IndexUser'],
            [],
        ], $router->match('/users'));
        $this->assertEquals([
            [
                'POST' => 'StoreUser',
                'PATCH' => 'UpdateUser',
                'PUT' => 'UpdateUser',
                'DELETE' => 'DestroyUser',
            ],
            ['user_id' => '123'],
        ], $router->match('/users/123'));
    }
}
