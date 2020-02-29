<?php

use Illuminate\Config\Repository;
use LaravelBridge\Scratch\Application as LaravelBridge;
use MilesChou\Codegener\CodegenerServiceProvider;
use MilesChou\Phalog\Console\App;
use MilesChou\Phalog\Console\Commands\BuildCommand;
use MilesChou\Phalog\Providers\BaseServiceProvider;
use org\bovigo\vfs\vfsStream;

require dirname(__DIR__) . '/vendor/autoload.php';

return (static function () {
    $vfs = vfsStream::setup('view');

    $cwd = getcwd();

    $container = (new LaravelBridge($cwd))
        ->useConfigurationLoader()
        ->setupView(dirname(__DIR__) . '/resources/static', $vfs->url())
        ->setupProvider(BaseServiceProvider::class)
        ->setupProvider(CodegenerServiceProvider::class);

    $container->instance(vfsStream::class, $vfs);
    $container->bind(Repository::class, 'config');

    $container->bootstrap();

    $app = new App($container, $container->make('events'), 'dev-master');
    $app->add(new BuildCommand());

    return $app;
})();
