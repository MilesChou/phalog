<?php

namespace MilesChou\Phalog;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Factory as ViewFactory;
use MilesChou\Parkdown\Bridges\ParsedownMarkdownParser;
use MilesChou\Parkdown\Bridges\SymfonyYamlParser;
use MilesChou\Parkdown\Contracts\MarkdownParser;
use MilesChou\Parkdown\Contracts\YamlParser;
use MilesChou\Parkdown\Parser as Markdown;
use MilesChou\Phalog\Compilers\MarkdownCompiler;

class BaseServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->enableMarkdownCompiler();
    }

    public function register(): void
    {
        $this->app->bind(MarkdownParser::class, ParsedownMarkdownParser::class);
        $this->app->bind(YamlParser::class, SymfonyYamlParser::class);

        $this->app->singleton(Markdown::class, function () {
            return new Markdown($this->app);
        });

        $this->app->singleton(MarkdownCompiler::class, function () {
            $markdown = $this->app->make(Markdown::class);
            $files = $this->app['files'];
            $storagePath = $this->app['config']['view.compiled'];

            return new MarkdownCompiler($markdown, $files, $storagePath);
        });
    }

    protected function enableMarkdownCompiler(): void
    {
        /** @var ViewFactory $view */
        $view = $this->app->make('view');

        $view->getEngineResolver()->register('md', function () {
            return new CompilerEngine($this->app->make(MarkdownCompiler::class));
        });

        $view->addExtension('md', 'md');
    }
}
