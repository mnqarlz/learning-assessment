<?php

declare(strict_types=1);

use Slim\App; 
use App\Application\Middleware\JwtMiddleware;
use App\Infrastructure\Database\StudentDatabase;
use App\Infrastructure\Service\ConnectBasicTest;
use App\Infrastructure\Controller\AuthController;
use App\Infrastructure\Controller\UserController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request; 
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Infrastructure\Persistence\MySql\MySqlStudentRepository;

return function (App $app) {

    $app->group('/api/auth', function (Group $group): void {
        $group->post('/login', [AuthController::class, 'userLogin']);
    });

    $app->group('/api', function ($app) {
 
        $app->get('', function (Request $request, Response $response) {
            $response->getBody()->write( $_ENV['APP_ENV']);
            return $response;
        });

        // test db connect ke tak
        $app->get('/db-test', function (Request $request, Response $response, array $args) {
         
        
            $studentDb = $this->get(StudentDatabase::class);
 
            $repo = new MySqlStudentRepository($studentDb);
            try {
                $users = $repo->findAll();
                $response->getBody()->write("DB Test Success: " . json_encode($users));

            } catch (PDOException $e) {
                $response->getBody()->write("DB Test Failed: " . $e->getMessage());
            }
        
            return $response;
        });

        $app->group('/users', function (Group $group) {
            $group->get('', [UserController::class, 'index']);
            $group->get('/{userId}', [UserController::class, 'show']);      
            $group->post('', [UserController::class, 'store']);
        });
        
    })->add(JwtMiddleware::class);
 
};
