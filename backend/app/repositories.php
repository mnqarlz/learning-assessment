<?php

declare(strict_types=1);
  
use DI\ContainerBuilder;
use App\Domain\User\Repository\UserRepository;
use App\Infrastructure\Persistence\MySqlUserRepository;

return function (ContainerBuilder $containerBuilder) { 
    $containerBuilder->addDefinitions([
        UserRepository::class => \DI\autowire(MySqlUserRepository::class),
    ]);
};
