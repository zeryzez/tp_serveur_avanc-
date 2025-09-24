<?php

namespace toubilib\core\application\ports\api;

use toubilib\core\application\dto\RdvDTO;

interface ServiceRdvInterface {
    public function getRdvById(string $id): ?RdvDTO;
    public function listerCreneauxOccupes(string $praticienId, string $dateDebut, string $dateFin): array;
}