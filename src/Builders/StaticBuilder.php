<?php

namespace MilesChou\Phalog\Builders;

use Illuminate\View\Factory as View;
use MilesChou\Codegener\Traits\Path;
use MilesChou\Codegener\Writer;
use Symfony\Component\Finder\Finder;

/**
 * Build the file with static path
 */
class StaticBuilder
{
    use Path;

    /**
     * @var View
     */
    private $view;

    /**
     * @var Writer
     */
    private $writer;

    public function __construct(View $view)
    {
        $this->view = $view;
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
            $filename = $file->getFilename();
            $pathname = $this->normalizeRelativePath($file->getRelativePath(), $filename);

            yield $pathname => $this->view->file($file->getPathname())->render();
        }
    }

    private function normalizeFilename(string $filename): string
    {
        [$filename] = explode('.', $filename, 2);

        return $filename;
    }

    private function normalizeRelativePath(string $relativePath, string $filename): string
    {
        $filename = $this->normalizeFilename($filename);

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
}
