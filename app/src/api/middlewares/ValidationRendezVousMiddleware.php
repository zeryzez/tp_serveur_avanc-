<?php
namespace toubilib\api\middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use toubilib\core\application\dto\InputRendezVousDTO;
use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\PatientRepositoryInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\RdvRepositoryInterface;

class ValidationRendezVousMiddleware
{
    private PraticienRepositoryInterface $praticienRepository;
    private PatientRepositoryInterface $patientRepository;
    private RdvRepositoryInterface $rdvRepository;

    public function __construct(
        PraticienRepositoryInterface $praticienRepository,
        PatientRepositoryInterface $patientRepository,
        RdvRepositoryInterface $rdvRepository
    ) {
        $this->praticienRepository = $praticienRepository;
        $this->patientRepository = $patientRepository;
        $this->rdvRepository = $rdvRepository;
    }

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

        // Validation métier

        // Vérifier que le praticien existe
        $praticien = $this->praticienRepository->findById($dto->praticien_id);
        if (!$praticien) {
            $response->getBody()->write(json_encode([
                'error' => "Le praticien n'existe pas."
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        // Vérifier que le patient existe
        $patient = $this->patientRepository->findById($dto->patient_id);
        if (!$patient) {
            $response->getBody()->write(json_encode([
                'error' => "Le patient n'existe pas."
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        // Vérifier que le motif de visite fait partie des motifs pour ce praticien
        $motifIds = array_map(fn($m) => (string)$m->getId(), $praticien->getMotifsVisite());
        if (!in_array($dto->motif_visite, $motifIds)) {
            $response->getBody()->write(json_encode([
                'error' => "Le motif de visite n'est pas valide pour ce praticien."
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        // Vérifier que le créneau horaire demandé est valide : jour ouvré et horaire possible
        $dateDebut = new \DateTime($dto->date_heure_debut);
        $dayOfWeek = (int)$dateDebut->format('N'); // 1 = Monday, 7 = Sunday
        if ($dayOfWeek > 5) {
            $response->getBody()->write(json_encode([
                'error' => "Le créneau doit être un jour ouvré (lundi à vendredi)."
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
        $hour = (int)$dateDebut->format('H');
        if ($hour < 8 || $hour >= 19) {
            $response->getBody()->write(json_encode([
                'error' => "L'horaire doit être entre 8h et 19h."
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        // Vérifier que le praticien est disponible pour le créneau horaire demandé
        $dateFin = clone $dateDebut;
        $dateFin->add(new \DateInterval('PT' . $dto->duree . 'M'));
        $dateDebutStr = $dateDebut->format('Y-m-d') . ' 00:00:00';
        $dateFinStr = $dateDebut->format('Y-m-d') . ' 23:59:59';
        $existingRdvs = $this->rdvRepository->findCreneauxByPraticienAndPeriode($dto->praticien_id, $dateDebutStr, $dateFinStr);
        foreach ($existingRdvs as $existingRdv) {
            $existingDebut = new \DateTime($existingRdv->getDateHeureDebut());
            $existingFin = new \DateTime($existingRdv->getDateHeureFin());
            if ($dateDebut < $existingFin && $dateFin > $existingDebut) {
                $response->getBody()->write(json_encode([
                    'error' => "Le praticien n'est pas disponible pour ce créneau."
                ]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        }

        // Transmission du DTO à l'action suivante
        $request = $request->withAttribute('inputRendezVousDTO', $dto);

        return $next($request, $response);
    }
}