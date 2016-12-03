<?php

namespace Emonkak\Router;

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
     * @var mixed[]
     */
    private $metadatas = [];

    /**
     * @var integer
     */
    private $maxPatternLength = 0;

    /**
     * {@inheritDoc}
     */
    public function route($path, $metadata)
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
        $this->metadatas[] = $metadata;
        $this->maxPatternLength = max(strlen($matchPattern), $this->maxPatternLength);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function match($path)
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

    /**
     * @return integer
     */
    protected function computeChunkSize()
    {
        return (int) ((0x8000 - 9) / ($this->maxPatternLength + 3));
    }

    /**
     * @param string   $path
     * @param string[] $patternChunk
     */
    protected function processChunk($path, array $patternChunk)
    {
        $pattern = '#^(?:' . implode('()|', $patternChunk) . '())$#';

        if (preg_match($pattern, $path, $matches)) {
            $index = count($matches) - 2;  // skip 0-index
            $pattern = '#^(?:' . $this->capturePatterns[$index] . ')$#';

            if (preg_match($pattern, $path, $matches)) {
                $metadata = $this->metadatas[$index];

                for ($i = 0, $l = (count($matches) >> 1) + 1; $i < $l; $i++) {
                    unset($matches[$i]);
                }

                return [$metadata, $matches];
            }
        }

        return null;
    }
}
