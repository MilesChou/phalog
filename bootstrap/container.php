<?php

use LaravelBridge\Scratch\Application;
use MilesChou\Phalog\BaseServiceProvider;

$container = new Application();
$container->setupView(
    dirname(__DIR__) . '/resources/pages',
    dirname(__DIR__) . '/resources/pages'
);

$container->setupCallableProvider(function ($app) {
    return new BaseServiceProvider($app);
});


return $container;
