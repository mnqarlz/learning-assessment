<?php

declare(strict_types=1);
 
use DI\ContainerBuilder;
use App\Domain\User\Service\UserService;

return function (ContainerBuilder $containerBuilder) { 
    $containerBuilder->addDefinitions([
      UserService::class => \DI\autowire(UserService::class),
    ]);
};
