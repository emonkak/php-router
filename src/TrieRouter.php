<?php

declare(strict_types=1);

namespace Emonkak\Router;

/**
 * @template THandler
 * @implements RoutableRouterInterface<THandler,string>
 */
class TrieRouter implements RoutableRouterInterface
{
    const CHILDREN = 0;
    const NAME = 1;
    const HANDLER = 2;

    const WILDCARD = '*';

    /**
     * @var array
     */
    private $root = [
        self::CHILDREN => [],
        self::NAME => null,
    ];

    /**
     * @param THandler $handler
     */
    public function addRoute(string $path, $handler): void
    {
        $node = &$this->root;

        if ($path !== '/') {
            $parts = explode('/', $this->trimRootSlash($path));

            foreach ($parts as $part) {
                if ($part !== '' && $part[0] === ':') {
                    $key = self::WILDCARD;
                    if (!isset($node[self::CHILDREN][$key])) {
                        $node[self::CHILDREN][$key] = [
                            self::CHILDREN => [],
                            self::NAME => substr($part, 1),
                        ];
                    }
                } else {
                    $key = $part;
                    if (!isset($node[self::CHILDREN][$key])) {
                        $node[self::CHILDREN][$key] = [
                            self::CHILDREN => [],
                            self::NAME => null,
                        ];
                    }
                }
                $node = &$node[self::CHILDREN][$key];
            }
        }

        $node[self::HANDLER] = $handler;
    }

    /**
     * {@inheritdoc}
     */
    public function match(string $path): ?array
    {
        $node = $this->root;
        $params = [];

        if ($path !== '/') {
            $parts = explode('/', $this->trimRootSlash($path));

            foreach ($parts as $part) {
                if (isset($node[self::CHILDREN][$part]) && $part !== self::WILDCARD) {
                    $node = $node[self::CHILDREN][$part];
                } else {
                    if (isset($node[self::CHILDREN][self::WILDCARD]) && $part !== '') {
                        $node = $node[self::CHILDREN][self::WILDCARD];
                        $params[$node[self::NAME]] = $part;
                    } else {
                        return null;
                    }
                }
            }
        }

        if (!isset($node[self::HANDLER])) {
            return null;
        }

        return [$node[self::HANDLER], $params];
    }

    public function trimRootSlash(string $path): string
    {
        return $path !== '' && $path[0] === '/' ? substr($path, 1) : $path;
    }
}
