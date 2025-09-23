<?php

namespace toubilib\core\application\usecases;

use toubilib\core\domain\repositories\PraticienRepositoryInterface;
use toubilib\core\application\dto\PraticienDTO;

class ServicePraticien implements ServicePraticienInterface
{
    private PraticienRepositoryInterface $praticienRepository;

    public function __construct(PraticienRepositoryInterface $praticienRepository)
    {
        $this->praticienRepository = $praticienRepository;
    }

    public function listerPraticiens(): array {
    	$praticiens = $this->praticienRepository->findAll();
    return array_map(function ($praticien) {
        return new PraticienDTO(
            $praticien->getNom(),
            $praticien->getPrenom(),
            $praticien->getVille(),
            $praticien->getSpecialite(),
            $praticien->getEmail()
        );
    }, $praticiens);
    }
}