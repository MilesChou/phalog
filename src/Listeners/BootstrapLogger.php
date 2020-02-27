<?php

namespace MilesChou\Phalog\Listeners;

use Illuminate\Console\Events\CommandStarting;
use LaravelBridge\Scratch\Application;
use Symfony\Component\Console\Logger\ConsoleLogger;

class BootstrapLogger
{
    /**
     * @var Application
     */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function handle(CommandStarting $event): void
    {
        $this->app->setupLogger('phalog', new ConsoleLogger($event->output), true);
    }
}
