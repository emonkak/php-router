<?php

declare(strict_types=1);

namespace Emonkak\Router;

/**
 * @template THandler
 * @implements RoutableRouterInterface<THandler,string>
 */
class RegexpRouter implements RoutableRouterInterface
{
    /**
     * @var string[]
     */
    private $matchPatterns = [];

    /**
     * @var string[]
     */
    private $capturePatterns = [];

    /**
     * @var THandler[]
     */
    private $handlers = [];

    /**
     * @var int
     */
    private $maxPatternLength = 0;

    /**
     * @param THandler $handler
     */
    public function addRoute(string $path, $handler): void
    {
        if ($path === '/') {
            $matchPattern = '/';
            $capturePattern = '/';
        } else {
            $parts = explode('/', $path);
            $matchParts = [];
            $captureParts = [];

            foreach ($parts as $part) {
                if ($part !== '' && $part[0] === ':') {
                    $matchParts[] = '[^/]+';
                    $captureParts[] = sprintf('(?P<%s>[^/]+)', substr($part, 1));
                } else {
                    $matchParts[] = $captureParts[] = preg_quote($part, '#');
                }
            }

            $matchPattern = implode('/', $matchParts);
            $capturePattern = implode('/', $captureParts);
        }

        $this->matchPatterns[] = $matchPattern;
        $this->capturePatterns[] = $capturePattern;
        $this->handlers[] = $handler;
        $this->maxPatternLength = max(strlen($matchPattern), $this->maxPatternLength);
    }

    /**
     * {@inheritdoc}
     */
    public function match(string $path): ?array
    {
        $chunkSize = $this->computeChunkSize();
        if ($chunkSize < 1) {
            throw new \OverflowException('The regular expression pattern is too large');
        }

        if (count($this->matchPatterns) < $chunkSize) {
            return $this->processChunk($path, $this->matchPatterns);
        } else {
            $chunks = array_chunk($this->matchPatterns, $chunkSize);

            foreach ($chunks as $chunk) {
                $match = $this->processChunk($path, $chunk);
                if ($match !== null) {
                    return $match;
                }
            }

            return null;
        }
    }

    protected function computeChunkSize(): int
    {
        return (int) ((0x8000 - 9) / ($this->maxPatternLength + 3));
    }

    /**
     * @param string[] $patternChunk
     * @return ?array{0:THandler,1:string[]}
     */
    protected function processChunk(string $path, array $patternChunk)
    {
        $pattern = '#^(?:' . implode('()|', $patternChunk) . '())$#';

        if (preg_match($pattern, $path, $matches)) {
            $index = count($matches) - 2;  // skip 0-index
            $pattern = '#^(?:' . $this->capturePatterns[$index] . ')$#';

            if (preg_match($pattern, $path, $matches)) {
                $handler = $this->handlers[$index];

                for ($i = 0, $l = (count($matches) >> 1) + 1; $i < $l; $i++) {
                    unset($matches[$i]);
                }

                return [$handler, $matches];
            }
        }

        return null;
    }
}
