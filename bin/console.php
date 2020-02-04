<?php

use Illuminate\Container\Container;
use MilesChou\Phalog\Application;

require dirname(__DIR__) . '/vendor/autoload.php';

/** @var Container $container */
$container = require dirname(__DIR__) . '/bootstrap/container.php';
$container->bootstrap();

$app = new Application($container, $container->make('events'), 'dev-master');

$app->run();
