<?php

declare(strict_types=1);

use PDO;
use Monolog\Logger;
use DI\ContainerBuilder;
use Psr\Log\LoggerInterface; 
use Monolog\Handler\StreamHandler;
use PHPMailer\PHPMailer\PHPMailer;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use App\Infrastructure\Service\MailerService;
use App\Application\Settings\SettingsInterface;
use App\Infrastructure\Database\StudentDatabase;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Validation\Factory as ValidationFactory;
use Illuminate\Translation\Translator as IlluminateTranslator;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        PDO::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            $db = $settings->get('db');

            $dsn = sprintf(
                '%s:host=%s;port=%s;dbname=%s;',
                $db['driver'],
                $db['host'],
                $db['port'],
                $db['database']
            );

            return new PDO($dsn, $db['username'], $db['password'], $db['flags']);
        },

        StudentDatabase::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            $db = $settings->get('student_db');

            $dsn = sprintf(
                '%s:host=%s;port=%s;dbname=%s;',
                $db['driver'],
                $db['host'],
                $db['port'],
                $db['database']
            );

            return new StudentDatabase($db);
        },

        MailerService::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            $mailerSettings = $settings->get('mailer');

            $mailer = new PHPMailer(true);  // Create the PHPMailer instance
            $mailer->isSMTP();
            $mailer->Host = $mailerSettings['host'];
            $mailer->SMTPAuth = true;
            $mailer->Username = $mailerSettings['username'];
            $mailer->Password = $mailerSettings['password'];
            $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mailer->Port = $mailerSettings['port'];
            $mailer->setFrom($mailerSettings['from_email'], $mailerSettings['from_name']);  // Set the "From" address

            return new MailerService($mailer);
        },

        Translator::class => function (ContainerInterface $c) {
            $loader = new \Illuminate\Translation\FileLoader(
                new \Illuminate\Filesystem\Filesystem(),
                __DIR__ . '/../resources/lang'
            );
            
            return new IlluminateTranslator($loader, 'en');
        },
        
        ValidationFactory::class => function (ContainerInterface $c) {
            $translator = $c->get(Translator::class);
            return new ValidationFactory($translator, null);

        },
    ]);
};
