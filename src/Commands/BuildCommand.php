<?php

namespace MilesChou\Phalog\Commands;

use Illuminate\Console\Command;
use Illuminate\View\Factory as View;
use MilesChou\Codegener\Traits\Path;
use MilesChou\Codegener\Writer;
use Symfony\Component\Finder\Finder;

class BuildCommand extends Command
{
    use Path;

    protected $name = 'build';

    protected $signature = 'build {--output-dir=dist} {--input-dir=resources}';

    public function handle(View $view, Writer $writer): int
    {
        $outputDir = $this->formatPath((string)$this->input->getOption('output-dir'));
        $inputDir = $this->formatPath((string)$this->input->getOption('input-dir'));

        $writer->setBasePath($outputDir);

        $finder = new Finder();
        $finder->files()->name('*.md')->in($inputDir);

        if (!$finder->hasResults()) {
            $this->output->warning('No file found');
            return 1;
        }

        foreach ($finder as $file) {
            $filename = $file->getFilenameWithoutExtension() . '.html';
            $pathname = $file->getRelativePath() . '/' . $filename;

            $writer->overwrite($pathname, $view->file($file->getPathname(), ['title' => 'test'])->render());
        }

        return 0;
    }
}
