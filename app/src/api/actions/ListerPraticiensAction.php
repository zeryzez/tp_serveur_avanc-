<?php

namespace toubilib\api\actions;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use toubilib\core\application\usecases\ServicePraticienInterface;

class ListerPraticiensAction
{
    private ServicePraticienInterface $servicePraticien;

    public function __construct(ServicePraticienInterface $servicePraticien)
    {
        $this->servicePraticien = $servicePraticien;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $praticiens = $this->servicePraticien->listerPraticiens();

        $response->getBody()->write(json_encode([
            'status' => 'success',
            'data' => $praticiens
        ]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
