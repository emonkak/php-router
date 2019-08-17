<?php

namespace Emonkak\Router;

class TrieRouter implements RoutableRouterInterface
{
    const CHILDREN = 0;
    const NAME     = 1;
    const DATA     = 2;

    const WILDCARD = '*';

    /**
     * @var array
     */
    private $root = [
        self::CHILDREN => [],
        self::NAME => null,
    ];

    /**
     * {@inheritDoc}
     */
    public function route($path, $data)
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

        $node[self::DATA] = $data;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function match($path)
    {
        $node = $this->root;
        $params = [];

        if ($path !== '/') {
            $parts = explode('/', ltrim($path, '/'));

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

        if (!isset($node[self::DATA])) {
            return null;
        }

        return [$node[self::DATA], $params];
    }
}
