<?php

namespace toubilib\infra\repositories;

use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface;
use toubilib\core\domain\entities\praticien\Praticien;
use toubilib\core\domain\entities\praticien\MotifVisite;
use toubilib\core\domain\entities\praticien\MoyenPaiement;

class PDOPraticienRepository implements PraticienRepositoryInterface
{

    private \PDO $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findAll(): array {
        $stmt = $this->pdo->query('SELECT p.*, s.libelle FROM praticien p JOIN specialite s ON p.specialite_id = s.id');
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return array_map(function ($row) {
            $praticien = new Praticien(
                $row['id'],
                $row['nom'],
                $row['prenom'],
                $row['ville'],
                $row['email'],
                $row['telephone'],
                $row['libelle'],
                $row['structure_id'],
                $row['rpps_id'],
                (bool)$row['organisation'],
                (bool)$row['nouveau_patient'],
                $row['titre']
            );

            $this->loadMotifsVisite($praticien);
            $this->loadMoyensPaiement($praticien);

            return $praticien;
        }, $results);
    }

    public function findById(string $id): ?Praticien
    {
        $stmt = $this->pdo->prepare('SELECT p.*, s.libelle FROM praticien p JOIN specialite s ON p.specialite_id = s.id WHERE p.id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        $praticien = new Praticien(
            $row['id'],
            $row['nom'],
            $row['prenom'],
            $row['ville'],
            $row['email'],
            $row['telephone'],
            $row['libelle'],
            $row['structure_id'],
            $row['rpps_id'],
            (bool)$row['organisation'],
            (bool)$row['nouveau_patient'],
            $row['titre']
        );

        $this->loadMotifsVisite($praticien);
        $this->loadMoyensPaiement($praticien);

        return $praticien;
    }

    private function loadMotifsVisite(Praticien $praticien): void
    {
        $stmt = $this->pdo->prepare('
            SELECT mv.id, mv.libelle
            FROM motif_visite mv
            JOIN praticien2motif p2m ON mv.id = p2m.motif_id
            WHERE p2m.praticien_id = :praticien_id
        ');
        $stmt->execute(['praticien_id' => $praticien->getId()]);
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($results as $row) {
            $motif = new MotifVisite($row['id'], 0, $row['libelle']);
            $praticien->addMotifVisite($motif);
        }
    }

    private function loadMoyensPaiement(Praticien $praticien): void
    {
        $stmt = $this->pdo->prepare('
            SELECT mp.id, mp.libelle
            FROM moyen_paiement mp
            JOIN praticien2moyen p2m ON mp.id = p2m.moyen_id
            WHERE p2m.praticien_id = :praticien_id
        ');
        $stmt->execute(['praticien_id' => $praticien->getId()]);
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($results as $row) {
            $moyen = new MoyenPaiement($row['id'], $row['libelle']);
            $praticien->addMoyenPaiement($moyen);
        }
    }
}
