<?php

use toubilib\api\actions\ListerPraticiensAction;
use toubilib\core\application\ports\api\ServicePraticienInterface;

use toubilib\core\application\ports\api\ServiceRdvInterface;
use toubilib\api\actions\ListerCreneauxOccupes;
use toubilib\api\actions\AfficherRdvAction;

use toubilib\api\actions\CreerRendezVousAction;
use toubilib\api\middleware\ValidationRendezVousMiddleware;

return [
    ListerPraticiensAction::class => function($container) {
        return new ListerPraticiensAction($container->get(ServicePraticienInterface::class));
    },
    ListerCreneauxOccupes::class => function($container) {
        return new ListerCreneauxOccupes($container->get(ServiceRdvInterface::class));
    },
    AfficherRdvAction::class => function($container) {
        return new AfficherRdvAction($container->get(ServiceRdvInterface::class));
    },
    CreerRendezVousAction::class => function($container) {
        return new CreerRendezVousAction($container->get(ServiceRdvInterface::class));
    },
    ValidationRendezVousMiddleware::class => function($container) {
        return new ValidationRendezVousMiddleware();
    }
];