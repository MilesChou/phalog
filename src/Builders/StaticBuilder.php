<?php

namespace MilesChou\Phalog\Builders;

use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Factory as View;
use MilesChou\Codegener\Traits\Path;
use MilesChou\Codegener\Writer;
use MilesChou\Parkdown\Parser;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Build the file with static path
 */
class StaticBuilder
{
    use Path;

    /**
     * @var Repository
     */
    private $config;

    /**
     * @var Parser
     */
    private $markdown;

    /**
     * @var View
     */
    private $view;

    /**
     * @var Writer
     */
    private $writer;

    /**
     * @param View $view
     * @param Parser $markdown
     * @param Repository $config
     */
    public function __construct(View $view, Parser $markdown, Repository $config)
    {
        $this->view = $view;
        $this->markdown = $markdown;
        $this->config = $config;
    }

    /**
     * @param string $path
     * @return iterable<string>
     */
    public function build(string $path): iterable
    {
        $this->setBasePath($path);

        $finder = new Finder();
        $finder->files()->name('*.md')->in($this->formatPath('static'));

        if (!$finder->hasResults()) {
            return [];
        }

        foreach ($finder as $file) {
            $outputPath = $this->getRelativePath($file);
            $pathname = $file->getPathname();

            $fs = new Filesystem();
            $content = $fs->get($pathname);
            $frontMatter = $this->markdown->parse($content)->frontMatter();

            $view = $this->view->file(
                $pathname,
                $frontMatter,
                $this->config->get('global')
            );

            yield $outputPath => $view->render();
        }
    }

    private function getRelativePath(SplFileInfo $file): string
    {
        $relativePath = $file->getRelativePath();

        $filename = $this->normalizeFilename($file->getFilename());

        if ('index' === $filename) {
            $filename .= '.html';
        } else {
            $filename .= '/index.html';
        }

        if (empty($relativePath)) {
            return $filename;
        }

        return $relativePath . '/' . $filename;
    }

    private function normalizeFilename(string $filename): string
    {
        [$filename] = explode('.', $filename, 2);

        return $filename;
    }
}
