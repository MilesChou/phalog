<?php

namespace MilesChou\Phalog;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Factory as ViewFactory;
use League\CommonMark\Converter as Markdown;
use League\CommonMark\DocParser;
use League\CommonMark\Environment;
use League\CommonMark\HtmlRenderer;
use MilesChou\Phalog\Compilers\MarkdownCompiler;

class BaseServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->enableMarkdownCompiler();
    }

    public function register()
    {
        $this->app->singleton(Markdown::class, function () {
            $environment = Environment::createCommonMarkEnvironment();

            $docParser = new DocParser($environment);
            $htmlRenderer = new HtmlRenderer($environment);

            return new Markdown($docParser, $htmlRenderer);
        });

        $this->app->singleton(MarkdownCompiler::class, function (Container $app) {
            $markdown = $app->make(Markdown::class);
            $files = $app['files'];
            $storagePath = $app['config']['view.compiled'];

            return new MarkdownCompiler($markdown, $files, $storagePath);
        });
    }

    protected function enableMarkdownCompiler(): void
    {
        /** @var ViewFactory $view */
        $view = $this->app->make('view');

        $view->getEngineResolver()->register('md', function () {
            $compiler = $this->app->make(MarkdownCompiler::class);

            return new CompilerEngine($compiler);
        });

        $view->addExtension('md', 'md');
    }
}
