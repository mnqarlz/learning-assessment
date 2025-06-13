<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator as IlluminateTranslator;
use Illuminate\Validation\Factory as ValidationFactory;

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
