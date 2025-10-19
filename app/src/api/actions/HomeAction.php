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
                'GET /rdvs/{id}' => 'Consulter un rendez-vous',
                // http://localhost:6080/praticiens/4305f5e9-be5a-4ccf-8792-7e07d7017363/agenda?date_debut=2025-12-04%2000:00:00&date_fin=2025-12-04%2023:59:59 url de test
                "GET /praticiens/{id}/agenda?date_debut=YYYY-MM-DD%2000:00:00&date_fin=YYYY-MM-DD%2023:59:59" => "Consulter l'agenda d'un praticien sur une période donnée (préciser les heures pour inclure toute la journée)",
                "POST /rdvs/{id}" => "Annuler un rendez-vous",
                'POST /auth/login' => 'Authentification (email + password)'

            ]
        ]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
