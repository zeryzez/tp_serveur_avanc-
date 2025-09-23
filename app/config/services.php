<?php
use toubilib\core\domain\repositories\PraticienRepositoryInterface;
use toubilib\core\infrastructure\repositories\PDOPraticienRepository;
use toubilib\core\application\usecases\ServicePraticien;
use toubilib\core\application\usecases\ServicePraticienInterface;

return [
    'pdo.praticien' => function($container) {
        $settings = $container->get('db.praticien');
        $dsn = "pgsql:host={$settings['db.praticien.host']};dbname={$settings['db.praticien.name']}";
        return new PDO($dsn, $settings['db.praticien.user'], $settings['db.praticien.pass']);
    },

    PraticienRepositoryInterface::class => function($container) {
        return new PDOPraticienRepository($container->get('pdo.praticien'));
    },

    ServicePraticienInterface::class => function($container) {
        return new ServicePraticien($container->get(PraticienRepositoryInterface::class));
    },
];