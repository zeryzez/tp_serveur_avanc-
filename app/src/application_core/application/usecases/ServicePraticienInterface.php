<?php

namespace toubilib\core\application\usecases;

interface ServicePraticienInterface
{
    /**
     * Liste tous les praticiens.
     *
     * @return array
     */
    public function listerPraticiens(): array;
}