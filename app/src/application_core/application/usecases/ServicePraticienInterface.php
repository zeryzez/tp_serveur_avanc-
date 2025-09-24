<?php

namespace toubilib\core\application\usecases;

use toubilib\core\application\dto\PraticienDTO;

interface ServicePraticienInterface
{
    /**
     * Liste tous les praticiens.
     *
     * @return array
     */
    public function listerPraticiens(): array;

    /**
     * Récupère le détail d'un praticien par son ID.
     *
     * @param string $id
     * @return PraticienDTO|null
     */
    public function getDetailPraticien(string $id): ?PraticienDTO;
}
