<?php

use LaravelBridge\Scratch\Application;

$container = new Application();
$container->setupView(
    dirname(__DIR__) . '/resources/views',
    dirname(__DIR__) . '/resources/views'
);

return $container;
