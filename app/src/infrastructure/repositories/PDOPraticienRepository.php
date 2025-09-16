<?php

namespace toubilib\infra\repositories;



class PDOPraticienRepository implements PraticienRepositoryInterface
{

    private \PDO $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }
 
    public function findAll(): array {
        $stmt = $this->pdo->query('SELECT * FROM praticiens');
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return array_map(function ($row) {
            return new Praticien(
                $row['nom'],
                $row['prenom'],
                $row['ville'],
                $row['specialite'],
                $row['email']
            );
        }, $results);
    }

}