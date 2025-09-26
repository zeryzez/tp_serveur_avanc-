<?php
namespace toubilib\api\middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use toubilib\core\application\dto\InputRendezVousDTO;

class ValidationRendezVousMiddleware
{
    public function __invoke(Request $request, Response $response, callable $next)
    {
        $data = $request->getParsedBody();

        // Contrôles de présence
        $required = ['praticien_id', 'patient_id', 'date_heure_debut', 'motif_visite', 'duree'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $response->getBody()->write(json_encode([
                    'error' => "Champ $field manquant"
                ]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        }

        // Contrôles de format
        if (!preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $data['date_heure_debut'])) {
            $response->getBody()->write(json_encode([
                'error' => "Format date_heure_debut invalide"
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
        if (!is_numeric($data['duree']) || intval($data['duree']) <= 0) {
            $response->getBody()->write(json_encode([
                'error' => "La durée doit être un entier positif"
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        // Création du DTO
        $dto = new InputRendezVousDTO(
            $data['praticien_id'],
            $data['patient_id'],
            $data['date_heure_debut'],
            $data['motif_visite'],
            intval($data['duree'])
        );

        // Transmission du DTO à l'action suivante
        $request = $request->withAttribute('inputRendezVousDTO', $dto);

        return $next($request, $response);
    }
}