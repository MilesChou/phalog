<?php

namespace MilesChou\Phalog\Console\Commands;

use Illuminate\Console\Command;
use MilesChou\Codegener\Traits\Path;
use MilesChou\Codegener\Writer;
use MilesChou\Phalog\Builders\StaticBuilder;

class BuildCommand extends Command
{
    use Path;

    protected $name = 'build';

    protected $signature = 'build {--output-dir=dist} {--input-dir=resources}';

    public function handle(StaticBuilder $staticBuilder, Writer $writer): int
    {
        $outputDir = $this->formatPath((string)$this->input->getOption('output-dir'));
        $inputDir = $this->formatPath((string)$this->input->getOption('input-dir'));

        $writer->setBasePath($outputDir);

        $contents = $staticBuilder->build($inputDir);

        $writer->overwriteMass($contents);

        return 0;
    }
}
