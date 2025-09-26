<?php
namespace toubilib\core\application\usecases;

use toubilib\core\application\ports\spi\repositoryInterfaces\RdvRepositoryInterface;
use toubilib\core\application\dto\RdvDTO;
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

    public function annulerRendezVous(int $idRdv): void {
        $rdv = $this->rdvrepository->findById($idRdv);
        if (!$rdv) {
            throw new Exception("Le rendez-vous n'existe pas.");
        }
        $rdv->annuler();
        $this->rdvRepository->save($rdv);
    }
}