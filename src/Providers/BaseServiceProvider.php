<?php

namespace MilesChou\Phalog\Providers;

use Illuminate\Console\Events\CommandStarting;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Factory as ViewFactory;
use MilesChou\Parkdown\Bridges\ParsedownMarkdownParser;
use MilesChou\Parkdown\Bridges\SymfonyYamlParser;
use MilesChou\Parkdown\Contracts\MarkdownParser;
use MilesChou\Parkdown\Contracts\YamlParser;
use MilesChou\Parkdown\Parser as Parkdown;
use MilesChou\Phalog\Listeners\BootstrapLogger;
use MilesChou\Phalog\View\Engines\BladeMarkdownEngine;
use MilesChou\Phalog\View\Engines\MarkdownEngine;

class BaseServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Event::listen(CommandStarting::class, BootstrapLogger::class);
    }

    public function register(): void
    {
        $this->registerParsers();

        $this->afterResolvingViewService();
    }

    private function registerParsers(): void
    {
        $this->app->bind(MarkdownParser::class, ParsedownMarkdownParser::class);
        $this->app->bind(YamlParser::class, SymfonyYamlParser::class);
    }

    private function afterResolvingViewService(): void
    {
        $this->app->afterResolving(ViewFactory::class, function (ViewFactory $view) {
            $resolver = $view->getEngineResolver();
            $resolver->register('markdown', function () {
                return $this->app->make(MarkdownEngine::class);
            });

            $resolver->register('blade-markdown', function () use ($resolver) {
                return new BladeMarkdownEngine(
                    $resolver->resolve('blade'),
                    $this->app->make(Parkdown::class)
                );
            });

            $view->addExtension('md', 'markdown');
            $view->addExtension('blade.md', 'blade-markdown');
        });
    }
}
