<?php
namespace toubilib\infra\repositories;

use toubilib\core\application\ports\spi\repositoryInterfaces\RdvRepositoryInterface;
use toubilib\core\domain\entities\rdv\RDV;
use PDO;

class PDORdvRepository implements RdvRepositoryInterface {
    private PDO $pdo;
    public function __construct(PDO $pdo) { $this->pdo = $pdo; }

    public function findById(string $id): ?RDV {
        $stmt = $this->pdo->prepare('SELECT * FROM rdv WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        if (!$row) return null;
        return new RDV(
            $row['id'],
            $row['praticien_id'],
            $row['patient_id'],
            $row['patient_email'],
            $row['date_heure_debut'],
            $row['date_heure_fin'],
            $row['status'],
            $row['duree'],
            $row['date_creation'],
            $row['motif_visite']
        );
    }

    public function findCreneauxByPraticienAndPeriode(string $praticienId, string $dateDebut, string $dateFin): array {
        $stmt = $this->pdo->prepare('SELECT * FROM rdv WHERE praticien_id = :pid AND date_heure_debut >= :deb AND date_heure_fin <= :fin');
        $stmt->execute(['pid' => $praticienId, 'deb' => $dateDebut, 'fin' => $dateFin]);
        $rows = $stmt->fetchAll();
        $result = [];
        foreach ($rows as $row) {
            $result[] = new RDV(
                $row['id'],
                $row['praticien_id'],
                $row['patient_id'],
                $row['patient_email'],
                $row['date_heure_debut'],
                $row['date_heure_fin'],
                $row['status'],
                $row['duree'],
                $row['date_creation'],
                $row['motif_visite']
            );
        }
        return $result;
    }
}