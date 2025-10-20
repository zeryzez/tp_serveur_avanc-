<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use toubilib\api\actions\HomeAction;
use toubilib\api\actions\ListerPraticiensAction;
use toubilib\api\actions\AfficherDetailPraticienAction;
use toubilib\api\actions\ListerCreneauxOccupes;
use toubilib\api\actions\AfficherRdvAction;
use toubilib\api\actions\AfficherAgendaPraticienAction;
use toubilib\api\actions\CreerRendezVousAction;
use toubilib\api\middlewares\ValidationRendezVousMiddleware;
use toubilib\api\middlewares\AuthzRendezVousMiddleware;
use toubilib\api\actions\AnnulerRDVAction;
use toubilib\api\actions\LoginAction;
use toubilib\api\actions\SigninAction;

return function( \Slim\App $app):\Slim\App {

    $app->get('/', HomeAction::class);
    $app->get('/praticiens', ListerPraticiensAction::class);
    $app->get('/praticiens/{id}', AfficherDetailPraticienAction::class);
    $app->get('/praticiens/{id}/creneaux', ListerCreneauxOccupes::class);
    $app->get('/rdvs/{id}', AfficherRdvAction::class)->setName('rdv-detail')->add(AuthzRendezVousMiddleware::class);
    $app->get('/praticiens/{id}/agenda', AfficherAgendaPraticienAction::class)->setName('agenda-praticien')->add(AuthzRendezVousMiddleware::class);
    $app->post('/rdvs/{id}/annuler', AnnulerRDVAction::class);
    $app->post('/rdvs', CreerRendezVousAction::class)->add(ValidationRendezVousMiddleware::class);
    $app->post('/auth/login', LoginAction::class);
    
    // Route pour l'authentification (signin) - Exercice 2
    $app->post('/auth/signin', SigninAction::class);

    return $app;
};
