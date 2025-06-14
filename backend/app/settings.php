<?php

declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
 

return function (ContainerBuilder $containerBuilder) {

    // Global Settings Object
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new Settings([
                'displayErrorDetails' => true, // Should be set to false in production
                'logError'            => false,
                'logErrorDetails'     => false,
                'logger' => [
                    'name' => 'slim-app',
                    'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                    'level' => \Monolog\Level::Debug,
                ],
                'db' => [
                    'driver'    => $_ENV['DB_DRIVER'],
                    'host'      => $_ENV['DB_HOST'],
                    'port'      => $_ENV['DB_PORT'],
                    'database'  => $_ENV['DB_DATABASE'],
                    'username'  => $_ENV['DB_USERNAME'],
                    'password'  => $_ENV['DB_PASSWORD'], 
                    'flags'     => [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ],
                ],
                "student_db" => [
                    'driver'    => $_ENV['STUDENT_DB_DRIVER'],
                    'host'      => $_ENV['STUDENT_DB_HOST'],
                    'port'      => $_ENV['STUDENT_DB_PORT'],
                    'database'  => $_ENV['STUDENT_DB_DATABASE'],
                    'username'  => $_ENV['STUDENT_DB_USERNAME'],
                    'password'  => $_ENV['STUDENT_DB_PASSWORD'], 
                    'flags'     => [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ],
                ], 
                "mailer" => [
                    'host'     => $_ENV['MAIL_HOST'] ,
                    'port'     => $_ENV['MAIL_PORT'],
                    'username' => $_ENV['MAIL_USERNAME'],
                    'password' => $_ENV['MAIL_PASSWORD'],
                    'from_email' => $_ENV['MAIL_FROM_EMAIL'],
                    'from_name'  => $_ENV['MAIL_FROM_NAME'] ,
                ]
            ]);
        }
    ]);
};
