<?php

declare(strict_types=1);

use Slim\App; 
use App\Infrastructure\Controller\UserController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write( $_ENV['APP_ENV']);
        return $response;
    });

    // test db connect ke tak
    $app->get('/db-test', function (Request $request, Response $response, array $args) {
        $pdo = $this->get(PDO::class);
    
        try {
            $stmt = $pdo->query('SELECT 1');
            $result = $stmt->fetch();
    
            $response->getBody()->write("DB Test Passed. Result: " . json_encode($result));
        } catch (PDOException $e) {
            $response->getBody()->write("DB Test Failed: " . $e->getMessage());
        }
    
        return $response;
    });
    

    $app->group('/users', function (Group $group) {
        $group->get('', [UserController::class, 'index']);   // POST /users
        $group->get('/{userId}', [UserController::class, 'show']);      
        $group->post('', [UserController::class, 'store']);   // POST /users
    });
};
