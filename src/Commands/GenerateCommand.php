<?php

namespace MilesChou\Phalog\Commands;

use Illuminate\Console\Command;
use MilesChou\Codegener\Traits\Path;
use Symfony\Component\Finder\Finder;

class GenerateCommand extends Command
{
    use Path;

    protected $name = 'generate';

    protected $signature = 'generate {--output-dir=dist} {--input-dir=}';

    public function handle(): int
    {
        $outputDir = $this->formatPath((string)$this->input->getOption('output-dir'));
        $inputDir = $this->formatPath((string)$this->input->getOption('input-dir'));

        $finder = new Finder();
        $finder->files()->name('*.md')->in($inputDir);

        if (!$finder->hasResults()) {
            $this->output->warning('No file found');
            return 0;
        }

        return 0;
    }
}
