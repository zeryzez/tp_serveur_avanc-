<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

return function( \Slim\App $app):\Slim\App {

    $app->get('/user-stories', \jira\api\actions\GetAllUserStoriesAction::class)
        ->setName('user-stories-list');

    $app->post('/user-stories/{id}/change-status', \jira\api\actions\ChangeStatusAction::class)
        ->setName('user-stories-change-status');
        
    /**
     * CORS : options pour les requêtes preflight
     */
    $app->options('/{routes:.+}', function (Request $request, Response $response, $args): Response {
        return $response;
    });
    return $app;
};