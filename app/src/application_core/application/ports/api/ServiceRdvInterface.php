<?php

namespace toubilib\core\application\ports\api;

use toubilib\core\application\dto\RdvDTO;
use toubilib\core\application\dto\InputRendezVousDTO;

interface ServiceRdvInterface {
    public function getRdvById(string $id): ?RdvDTO;
    public function listerCreneauxOccupes(string $praticienId, string $dateDebut, string $dateFin): array;

    public function creerRendezVous(InputRendezVousDTO $dto): void;
    public function annulerRendezVous(int $idRdv): void;
}