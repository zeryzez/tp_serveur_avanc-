<?php
use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface;
use toubilib\infra\repositories\PDOPraticienRepository;
use toubilib\core\application\usecases\ServicePraticien;
use toubilib\core\application\ports\api\ServicePraticienInterface;

use toubilib\core\application\ports\spi\repositoryInterfaces\RdvRepositoryInterface;
use toubilib\infra\repositories\PDORdvRepository;
use toubilib\core\application\usecases\ServiceRdv;
use toubilib\core\application\ports\api\ServiceRdvInterface;

use toubilib\core\application\ports\spi\repositoryInterfaces\PatientRepositoryInterface;
use toubilib\infra\repositories\PDOPatientRepository;
use PDO;

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
        $dsn = "pgsql:host={$settings['db.rdv.host']};dbname={$settings['db.rdv.name']}";
        return new PDO($dsn, $settings['db.rdv.user'], $settings['db.rdv.pass']);
    },

    PraticienRepositoryInterface::class => function($container) {
        return new PDOPraticienRepository($container->get('pdo.praticien'));
    },

    ServicePraticienInterface::class => function($container) {
        return new ServicePraticien($container->get(PraticienRepositoryInterface::class));
    },

    RdvRepositoryInterface::class => function($container) {
        return new PDORdvRepository($container->get('pdo.rdv'));
    },

    PatientRepositoryInterface::class => function($container) {
        return new PDOPatientRepository($container->get('pdo.patient'));
    },

    ServiceRdvInterface::class => function($container) {
        return new ServiceRdv(
            $container->get(RdvRepositoryInterface::class),
            $container->get(PraticienRepositoryInterface::class),
            $container->get(PatientRepositoryInterface::class)
        );
    },
];
