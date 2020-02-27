<?php

namespace MilesChou\Phalog\Compilers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\Compiler;
use Illuminate\View\Compilers\CompilerInterface;
use MilesChou\Parkdown\Parser as Markdown;

/**
 * Markdown compiler class.
 */
class MarkdownCompiler extends Compiler implements CompilerInterface
{
    /**
     * @var Markdown
     */
    protected $markdown;

    /**
     * @param Markdown $markdown
     * @param Filesystem $files
     * @param string $cachePath
     * @return void
     */
    public function __construct(Markdown $markdown, Filesystem $files, string $cachePath)
    {
        parent::__construct($files, $cachePath);

        $this->markdown = $markdown;
    }

    public function compile($path)
    {
        $contents = $this->markdown->parse($this->files->get($path));

        $this->files->put($this->getCompiledPath($path), $contents->html());
    }

    /**
     * @return Filesystem
     */
    public function getFiles()
    {
        return $this->files;
    }
}
