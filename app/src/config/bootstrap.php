<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

use jira\api\middlewares\Cors;

$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/settings.php' );

$c=$builder->build();
$app = AppFactory::createFromContainer($c);

$app->addBodyParsingMiddleware();
$app->add(Cors::class);
$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware($c->get('displayErrorDetails'), false, false);
$errorHandler = $errorMiddleware->getDefaultErrorHandler();
$errorHandler->forceContentType('application/json');
$errorHandler->registerErrorRenderer('application/json', function ($exception, $displayErrorDetails) {
    return json_encode(['message' => $exception->getMessage()]);
});

$app = (require_once __DIR__ . '/routes.php')($app);
$routeParser = $app->getRouteCollector()->getRouteParser();

return $app;