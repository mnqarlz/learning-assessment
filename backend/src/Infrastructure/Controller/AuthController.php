<?php
namespace App\Infrastructure\Controller;
 
use App\Application\Controller\Controller; 
use App\Domain\User\Service\UserService;  
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Illuminate\Validation\Factory as ValidationFactory;
use App\Domain\Authentication\Exception\InvalidCredentialsException;
use App\Domain\User\Exception\UserNotFoundException;
use Firebase\JWT\JWT;

class AuthController extends Controller {

  private UserService $userService ; 

  public function __construct(\Psr\Log\LoggerInterface $logger, ValidationFactory $validator, UserService $userService ) {
      parent::__construct($logger, $validator); 
      $this->userService = $userService; 
  }

  public function userLogin(Request $request, Response $response): Response {
    $this->setContext($request, response: $response);
    $data = $request->getParsedBody();

    if (!is_array($data)) {
      return $this->respondWithError($response, "Wrong Structure", "Must be Array", 404);
    }

    $validation = $this->validateRequest($data, [
        'email' => 'required|email', 
        'password' => 'required|string|min:6|max:15'
    ]);

    if (!$validation['valid']) {
      return $this->respondWithValidationErrors($validation['errors']);
    } 

    $validatedData = $validation['data'];

    try{
      $user = $this->userService->authenticateUser(
        $validatedData['email'],
        $validatedData['password']
      );

      $payload = [
        'email' => $validatedData['email'], 
        'iat' => time(),
        'exp' => time() + 60 * 60 * 24, // 24 hours
      ];

      $secret = $_ENV['JWT_SECRET'] ?? throw new \RuntimeException('JWT_SECRET not configured');
      $token = JWT::encode($payload, $secret, 'HS256');
      $cookieHeader = $this->createSecureCookie('jwt', $token, time() +  60 * 60 * 24 );
  
      $response = $response->withStatus(201);
      $response->getBody()->write(json_encode([
          'statusCode' => 201,
          'data' => ['message' => 'Success']
      ]));

      $response = $response->withHeader('Content-Type', 'application/json');
      $response = $response->withHeader('Set-Cookie', $cookieHeader);

      return $response;

    }  catch (UserNotFoundException | InvalidCredentialsException $e) { 
      return $this->respondWithError($response, "Authentication Failed", $e->getMessage(), 401);
    }
  }

  private function createSecureCookie(string $name, string $value, int $expires): string  {
      $env = $_ENV['APP_ENV'] ?? 'development';
      $isProduction = $env === 'production';
      $secure = $isProduction && (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on');
      $sameSite = $isProduction ? 'Strict' : 'Lax';
  
      // Add Domain for local development
      $domain = $isProduction ? $_ENV['COOKIE_DOMAIN'] ?? '' : 'localhost';
      $domainPart = $domain ? "; Domain={$domain}" : '';
  
      return sprintf(
          '%s=%s;Expires=%s;Path=/api;HttpOnly;SameSite=%s%s%s',
          $name,
          urlencode($value),
          gmdate('D, d M Y H:i:s T', $expires),
          $sameSite,
          $secure ? ';Secure' : '',
          $domainPart
      );
  }
  
}