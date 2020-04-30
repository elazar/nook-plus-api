<?php

require __DIR__ . '/../vendor/autoload.php';

use NookPlus\AppServiceProvider;
use Pimple\Container;
use Slim\App;

$container = new Container;
$container->register(new AppServiceProvider);
$container[App::class]->run();
