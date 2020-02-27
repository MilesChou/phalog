<?php

namespace MilesChou\Phalog\View\Engines;

use Illuminate\Contracts\View\Engine;
use Illuminate\Filesystem\Filesystem;
use MilesChou\Parkdown\Parser as Parkdown;

class MarkdownEngine implements Engine
{
    /**
     * @var Parkdown
     */
    private $parser;

    /**
     * @var Filesystem
     */
    private $file;

    public function __construct(Filesystem $file, Parkdown $parser)
    {
        $this->file = $file;
        $this->parser = $parser;
    }

    /**
     * @param string $path
     * @param array<mixed> $data
     * @return string
     */
    public function get($path, array $data = [])
    {
        return $this->parser->parse($this->file->get($path))->html();
    }
}
