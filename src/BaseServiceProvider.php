<?php

namespace MilesChou\Phalog;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Factory as ViewFactory;
use MilesChou\Parkdown\Bridges\ParsedownMarkdownParser;
use MilesChou\Parkdown\Bridges\SymfonyYamlParser;
use MilesChou\Parkdown\Contracts\MarkdownParser;
use MilesChou\Parkdown\Contracts\YamlParser;
use MilesChou\Parkdown\Parser as Parkdown;
use MilesChou\Phalog\View\Engines\BladeMarkdownEngine;
use MilesChou\Phalog\View\Engines\MarkdownEngine;

class BaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerParsers();

        $this->afterResolvingViewService();
    }

    private function registerParsers(): void
    {
        $this->app->bind(MarkdownParser::class, ParsedownMarkdownParser::class);
        $this->app->bind(YamlParser::class, SymfonyYamlParser::class);

        $this->app->singleton(Parkdown::class, function () {
            return new Parkdown($this->app);
        });
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
