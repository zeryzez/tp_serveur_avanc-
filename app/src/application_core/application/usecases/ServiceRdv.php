<?php
namespace toubilib\core\application\usecases;

use toubilib\core\application\ports\spi\repositoryInterfaces\RdvRepositoryInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\PatientRepositoryInterface;
use toubilib\core\application\dto\RdvDTO;
use toubilib\core\application\dto\InputRendezVousDTO;
use toubilib\core\domain\entities\rdv\RDV;
use toubilib\core\application\ports\api\ServiceRdvInterface;
use Ramsey\Uuid\Uuid;


class ServiceRdv implements ServiceRdvInterface
{
    private RdvRepositoryInterface $rdvRepository;
    private PraticienRepositoryInterface $praticienRepository;
    private PatientRepositoryInterface $patientRepository;

    public function __construct(
        RdvRepositoryInterface $rdvRepository,
        PraticienRepositoryInterface $praticienRepository,
        PatientRepositoryInterface $patientRepository
    ) {
        $this->rdvRepository = $rdvRepository;
        $this->praticienRepository = $praticienRepository;
        $this->patientRepository = $patientRepository;
    }

    public function getRdvById(string $id): ?RdvDTO
    {
        $rdv = $this->rdvRepository->findById($id);
        if (!$rdv)
            return null;
        return new RdvDTO(
            $rdv->getId(),
            $rdv->getPraticienId(),
            $rdv->getPatientId(),
            $rdv->getPatientEmail(),
            $rdv->getDateHeureDebut(),
            $rdv->getDateHeureFin(),
            $rdv->getStatus(),
            $rdv->getDuree(),
            $rdv->getDateCreation(),
            $rdv->getMotifVisite()
        );
    }

    public function listerCreneauxOccupes(string $praticienId, string $dateDebut, string $dateFin): array {
        $rdvs = $this->rdvRepository->findCreneauxByPraticienAndPeriode($praticienId, $dateDebut, $dateFin);
        return array_map(function($rdv) {
            return new RdvDTO(
                $rdv->getId(),
                $rdv->getPraticienId(),
                $rdv->getPatientId(),
                $rdv->getPatientEmail(),
                $rdv->getDateHeureDebut(),
                $rdv->getDateHeureFin(),
                $rdv->getStatus(),
                $rdv->getDuree(),
                $rdv->getDateCreation(),
                $rdv->getMotifVisite()
            );
        }, $rdvs);
    }

    public function creerRendezVous(InputRendezVousDTO $dto): void {

        $praticien = $this->praticienRepository->findById($dto->praticien_id);
        if (!$praticien) {
            throw new \Exception("Le praticien n'existe pas.");
        }

        // Vérifier que le patient existe
        $patient = $this->patientRepository->findById($dto->patient_id);
        if (!$patient) {
            throw new \Exception("Le patient n'existe pas.");
        }

        $motifIds = array_map(fn($m) => (string)$m->getId(), $praticien->getMotifsVisite());
        if (!in_array($dto->motif_visite, $motifIds)) {
            throw new \Exception("Le motif de visite n'est pas valide pour ce praticien.");
        }

        // Récupérer le libellé du motif
        $motifLabel = '';
        foreach ($praticien->getMotifsVisite() as $motif) {
            if ((string)$motif->getId() === $dto->motif_visite) {
                $motifLabel = $motif->getLibelle();
                break;
            }
        }

        $id = Uuid::uuid4()->toString();

        // Vérifier que le créneau horaire demandé est valide : jour ouvré et horaire possible
        $dateDebut = new \DateTime($dto->date_heure_debut);
        $dayOfWeek = (int)$dateDebut->format('N'); // 1 = Monday, 7 = Sunday
        if ($dayOfWeek > 5) {
            throw new \Exception("Le créneau doit être un jour ouvré (lundi à vendredi).");
        }
        $hour = (int)$dateDebut->format('H');
        if ($hour < 8 || $hour >= 19) {
            throw new \Exception("L'horaire doit être entre 8h et 19h.");
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
                throw new \Exception("Le praticien n'est pas disponible pour ce créneau.");
            }
        }

        $id = uniqid();
        $date_heure_fin = $dateFin->format('Y-m-d H:i:s');
        $status = 1; // Assuming 1 for active/confirmed
        $date_creation = date('Y-m-d H:i:s');
        $patient_email = $patient->getEmail();

        $rdv = new RDV(
            $id,
            $dto->praticien_id,
            $dto->patient_id,
            $patient_email,
            $dto->date_heure_debut,
            $date_heure_fin,
            $status,
            $dto->duree,
            $date_creation,
            $motifLabel
        );

        $this->rdvRepository->save($rdv);
    }

    public function annulerRendezVous(string $idRdv): void
    {
        $rdv = $this->rdvRepository->findById($idRdv);
        if (!$rdv) {
            throw new \Exception("Le rendez-vous n'existe pas.");
        }
        $rdv->annuler();
        $this->rdvRepository->save($rdv);
    }

    public function getAgendaPraticien(string $praticienId, ?string $dateDebut = null, ?string $dateFin = null): array
    {
        if (!$dateDebut || !$dateFin) {
            $dateDebut = date('Y-m-d') . ' 00:00:00';
            $dateFin = date('Y-m-d') . ' 23:59:59';
        }

        $rdvs = $this->rdvRepository->findCreneauxByPraticienAndPeriode($praticienId, $dateDebut, $dateFin);

        $agenda = [];
        foreach ($rdvs as $rdv) {
            $agenda[] = [
                'id' => $rdv->getId(),
                'date' => $rdv->getDateHeureDebut(),
                'heure' => date('H:i', strtotime($rdv->getDateHeureDebut())),
                'duree' => $rdv->getDuree(),
                'motif' => $rdv->getMotifVisite(),
                'etat' => $rdv->getStatus(),
                'patient' => [
                    'id' => $rdv->getPatientId(),
                    'lien' => '/patients/' . $rdv->getPatientId()
                ]
            ];
        }
        return $agenda;
    }
}