<?php

namespace toubilib\api\actions;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class HomeAction
{
    public function __invoke(Request $request, Response $response): Response
    {
        $response->getBody()->write(json_encode([
            'message' => 'Bienvenue sur l\'API Toubilib',
            'version' => '1.0.0',
            'endpoints' => [
                'GET /praticiens' => 'Lister tous les praticiens',
                'GET /praticiens/{id}' => 'Afficher les détails d\'un praticien',
                'GET /praticiens/{id}/creneaux' => 'Lister les créneaux occupés d\'un praticien',
                'GET /rdvs/{id}' => 'Consulter un rendez-vous'
            ]
        ]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
