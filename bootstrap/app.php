<?php

use LaravelBridge\Scratch\Application as LaravelBridge;
use MilesChou\Codegener\CodegenerServiceProvider;
use MilesChou\Phalog\App;
use MilesChou\Phalog\BaseServiceProvider;
use MilesChou\Phalog\Commands;
use org\bovigo\vfs\vfsStream;

require dirname(__DIR__) . '/vendor/autoload.php';

return (static function () {
    $container = (new LaravelBridge())
        ->setupView(dirname(__DIR__) . '/resources/pages', vfsStream::setup('view')->url())
        ->setupProvider(BaseServiceProvider::class)
        ->setupProvider(CodegenerServiceProvider::class)
        ->bootstrap();

    $app = new App($container, $container->make('events'), 'dev-master');
    $app->add(new Commands\BuildCommand());

    return $app;
})();
