<?php
use toubilib\core\domain\repositories\PraticienRepositoryInterface;
use toubilib\infra\repositories\PDOPraticienRepository;
use toubilib\core\application\usecases\ServicePraticien;
use toubilib\core\application\usecases\ServicePraticienInterface;

return [
    'pdo.praticien' => function($container) {
        $settings = $container->get('settings');
        $dsn = "pgsql:host={$settings['db.praticien.host']};port={$settings['db.praticien.port']};dbname={$settings['db.praticien.name']}";
        return new PDO($dsn, $settings['db.praticien.user'], $settings['db.praticien.pass']);
    },

    'pdo.auth' => function($container) {
        $settings = $container->get('settings');
        $dsn = "pgsql:host={$settings['db.auth.host']};port={$settings['db.auth.port']};dbname={$settings['db.auth.name']}";
        return new PDO($dsn, $settings['db.auth.user'], $settings['db.auth.pass']);
    },

    'pdo.patient' => function($container) {
        $settings = $container->get('settings');
        $dsn = "pgsql:host={$settings['db.patient.host']};port={$settings['db.patient.port']};dbname={$settings['db.patient.name']}";
        return new PDO($dsn, $settings['db.patient.user'], $settings['db.patient.pass']);
    },

    'pdo.rdv' => function($container) {
        $settings = $container->get('settings');
        $dsn = "pgsql:host={$settings['db.rdv.host']};port={$settings['db.rdv.port']};dbname={$settings['db.rdv.name']}";
        return new PDO($dsn, $settings['db.rdv.user'], $settings['db.rdv.pass']);
    },

    PraticienRepositoryInterface::class => function($container) {
        return new PDOPraticienRepository($container->get('pdo.praticien'));
    },

    ServicePraticienInterface::class => function($container) {
        return new ServicePraticien($container->get(PraticienRepositoryInterface::class));
    },
];
