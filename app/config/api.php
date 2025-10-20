<?php

use toubilib\api\actions\ListerPraticiensAction;
use toubilib\core\application\ports\api\ServicePraticienInterface;

use toubilib\core\application\ports\api\ServiceRdvInterface;
use toubilib\api\actions\ListerCreneauxOccupes;
use toubilib\api\actions\AfficherRdvAction;

use toubilib\api\actions\CreerRendezVousAction;
use toubilib\api\middleware\ValidationRendezVousMiddleware;
use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\PatientRepositoryInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\RdvRepositoryInterface;

use toubilib\api\actions\SigninAction;
use toubilib\api\provider\AuthProvider;

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
        return new ValidationRendezVousMiddleware(
            $container->get(PraticienRepositoryInterface::class),
            $container->get(PatientRepositoryInterface::class),
            $container->get(RdvRepositoryInterface::class)
        );
    },

    SigninAction::class => function($container) {
        return new SigninAction($container->get(AuthProvider::class));
    }
];