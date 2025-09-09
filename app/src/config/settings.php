<?php

use Psr\Container\ContainerInterface;
use jira\api\actions\GetAllUserStoriesAction;
use jira\core\application\ports\spi\UserStoryRepository;
use jira\core\application\ports\api\UserStoryServiceInterface;
use jira\core\application\usecases\UserStoryService;
use jira\infra\repositories\PgUserStoryRepository;

return [

    // settings
    'displayErrorDetails' => true,
    'logs.dir' => __DIR__ . '/../var/logs',
    'jira.db.config' => __DIR__ . '/jira.db.ini',
    
    // application
    GetAllUserStoriesAction::class=> function (ContainerInterface $c) {
        return new GetAllUserStoriesAction($c->get(UserStoryServiceInterface::class));
    },

    // service
    UserStoryServiceInterface::class => function (ContainerInterface $c) {
        return new UserStoryService($c->get(UserStoryRepository::class));
    },

    // infra
     'jira.pdo' => function (ContainerInterface $c) {
        $config = parse_ini_file($c->get('jira.db.config'));
        $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']}";
        $user = $config['username'];
        $password = $config['password'];
        return new \PDO($dsn, $user, $password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    },

    UserStoryRepository::class => fn(ContainerInterface $c) => new PgUserStoryRepository($c->get('jira.pdo')),
    
];

