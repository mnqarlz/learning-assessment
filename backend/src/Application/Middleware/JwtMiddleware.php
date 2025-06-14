<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class JwtMiddleware
{ 
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $cookies = $request->getCookieParams();
        $token = $cookies['jwt'] ?? '';

        if (!$token) {
            return $this->createErrorResponse('Token missing', 401);
        }

        try {
            $secret = $_ENV['JWT_SECRET'] ?? throw new \RuntimeException('JWT_SECRET not configured');
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));
            $request = $request->withAttribute('user', $decoded);
        } catch (ExpiredException $e) {
            return $this->createErrorResponse('Token expired', 401);
        } catch (SignatureInvalidException $e) {
            return $this->createErrorResponse('Invalid token signature', 401);
        } catch (\Exception $e) {
            return $this->createErrorResponse('Invalid token', 401);
        }

        return $handler->handle($request);
    }

    private function createErrorResponse(string $message, int $status): Response
    {
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write(json_encode(['error' => $message]));
        return $response
            ->withStatus($status)
            ->withHeader('Content-Type', 'application/json');
}
}