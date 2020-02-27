<?php

use LaravelBridge\Scratch\Application as LaravelBridge;
use MilesChou\Codegener\CodegenerServiceProvider;
use MilesChou\Phalog\App;
use MilesChou\Phalog\BaseServiceProvider;
use MilesChou\Phalog\Commands;
use org\bovigo\vfs\vfsStream;

require dirname(__DIR__) . '/vendor/autoload.php';

return (static function () {
    $vfs = vfsStream::setup('view');

    $container = (new LaravelBridge())
        ->setupView(dirname(__DIR__) . '/resources/pages', $vfs->url())
        ->setupProvider(BaseServiceProvider::class)
        ->setupProvider(CodegenerServiceProvider::class);

    $container->instance(vfsStream::class, $vfs);
    $container->bootstrap();

    $app = new App($container, $container->make('events'), 'dev-master');
    $app->add(new Commands\BuildCommand());

    return $app;
})();
