<?php

namespace MilesChou\Phalog\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\View;
use Illuminate\View\Factory;

class GenerateCommand extends Command
{
    protected $name = 'generate';

    public function handle(): int
    {
        /** @var Factory $view */
        $view = app('view');

        echo View::make('basic');

        return 0;
    }
}
