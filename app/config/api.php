<?php

use toubilib\api\actions\ListerPraticiensAction;
use toubilib\core\application\usecases\ServicePraticienInterface;

return [
    ListerPraticiensAction::class => function($container) {
        return new ListerPraticiensAction($container->get(ServicePraticienInterface::class));
    },
];