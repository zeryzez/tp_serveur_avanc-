<?php
use toubilib\core\domain\repositories\PraticienRepositoryInterface;
use toubilib\core\infrastructure\repositories\PDOPraticienRepository;
use toubilib\core\application\usecases\ServicePraticien;
use toubilib\core\application\usecases\ServicePraticienInterface;

return [
    'pdo' => function($container) {
        $settings = $container->get('settings');
        $dsn = "pgsql:host={$settings['db.host']};dbname={$settings['db.name']}";
        return new PDO($dsn, $settings['db.user'], $settings['db.pass']);
    },

    PraticienRepositoryInterface::class => function($container) {
        return new PDOPraticienRepository($container->get('pdo'));
    },

    ServicePraticienInterface::class => function($container) {
        return new ServicePraticien($container->get(PraticienRepositoryInterface::class));
    },
];