<?php

namespace MilesChou\Phalog;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Factory as ViewFactory;
use MilesChou\Phalog\Compilers\MarkdownCompiler;

class BaseServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->enableMarkdownCompiler();
    }

    public function register(): void
    {
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
