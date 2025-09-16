<?php

use DI\Container;
use Slim\Factory\AppFactory;

$settings = require __DIR__ . '/settings.php';
$services = require __DIR__ . '/services.php';
$api = require __DIR__ . '/api.php';

$container = new Container();

$container->set('settings', $settings);

foreach ($services as $key => $factory) {
    $container->set($key, $factory);
}

foreach ($api as $key => $factory) {
    $container->set($key, $factory);
}

AppFactory::setContainer($container);
$app = AppFactory::create();

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware($c->get('displayErrorDetails'), false, false)
    ->getDefaultErrorHandler()
    ->forceContentType('application/json')
;

$app = (require_once __DIR__ . '/../src/api/routes.php')($app);


return $app;