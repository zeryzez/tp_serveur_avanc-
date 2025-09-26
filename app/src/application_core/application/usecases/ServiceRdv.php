<?php
namespace toubilib\core\application\usecases;

use toubilib\core\application\ports\spi\repositoryInterfaces\RdvRepositoryInterface;
use toubilib\core\application\dto\RdvDTO;
use toubilib\core\application\dto\InputRendezVousDTO;
use toubilib\core\domain\entities\rdv\RDV;
use toubilib\core\application\ports\api\ServiceRdvInterface;

class ServiceRdv implements ServiceRdvInterface {
    private RdvRepositoryInterface $rdvRepository;
    public function __construct(RdvRepositoryInterface $rdvRepository) {
        $this->rdvRepository = $rdvRepository;
    }

    public function getRdvById(string $id): ?RdvDTO {
        $rdv = $this->rdvRepository->findById($id);
        if (!$rdv) return null;
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
        $id = uniqid();
        $dateDebut = new \DateTime($dto->date_heure_debut);
        $dateFin = clone $dateDebut;
        $dateFin->add(new \DateInterval('PT' . $dto->duree . 'M'));
        $date_heure_fin = $dateFin->format('Y-m-d H:i:s');
        $status = 1; // Assuming 1 for active/confirmed
        $date_creation = date('Y-m-d H:i:s');
        $patient_email = null;

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
            $dto->motif_visite
        );

        $this->rdvRepository->save($rdv);
    }

    public function annulerRendezVous(int $idRdv): void {
        $rdv = $this->rdvrepository->findById($idRdv);
        if (!$rdv) {
            throw new Exception("Le rendez-vous n'existe pas.");
        }
        $rdv->annuler();
        $this->rdvRepository->save($rdv);
    }
}