<?php

namespace MilesChou\Phalog\Compilers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\Compiler;
use Illuminate\View\Compilers\CompilerInterface;
use League\CommonMark\Converter;

/**
 * Markdown compiler class.
 */
class MarkdownCompiler extends Compiler implements CompilerInterface
{
    /**
     * @var Converter
     */
    protected $markdown;

    /**
     * @param Converter $markdown
     * @param Filesystem $files
     * @param string $cachePath
     * @return void
     */
    public function __construct(Converter $markdown, Filesystem $files, string $cachePath)
    {
        parent::__construct($files, $cachePath);

        $this->markdown = $markdown;
    }

    public function compile($path)
    {
        $contents = $this->markdown->convertToHtml($this->files->get($path));

        $this->files->put($this->getCompiledPath($path), $contents);
    }

    /**
     * @return Filesystem
     */
    public function getFiles()
    {
        return $this->files;
    }
}
