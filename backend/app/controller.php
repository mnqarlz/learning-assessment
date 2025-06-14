<?php

declare(strict_types=1);
  
use App\Infrastructure\Controller\AuthController;
use DI\ContainerBuilder; 
use App\Infrastructure\Controller\UserController;

return function (ContainerBuilder $containerBuilder) { 
    $containerBuilder->addDefinitions([
      UserController::class => \DI\autowire(UserController::class),
      AuthController::class => \DI\autowire(AuthController::class)
    ]);
};
