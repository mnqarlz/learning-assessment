<?php
declare(strict_types=1);

namespace App\Infrastructure\Controller;
use App\Domain\User\Model\User;
use App\Domain\User\Service\UserService;
use App\Application\Controller\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Illuminate\Validation\Factory as ValidationFactory;
use Ramsey\Uuid\Uuid; 

class UserController extends Controller {

  protected UserService $userService;

  public function __construct(\Psr\Log\LoggerInterface $logger, ValidationFactory $validator, UserService $userService )
  {
      parent::__construct($logger, $validator);
      $this->userService = $userService; 
  }

  public function index(Request $request, Response $response): Response
  { 
    $this->setContext($request, $response);

    $data = $this->userService->getUserList();

    return $this->respondWithData($data );
  }

  public function store(Request $request, Response $response): Response
  {
      $this->setContext($request, $response);
   
      $data = $request->getParsedBody();
      
      // Now validation should work
      $validation = $this->validateRequest($data, [
          'username' => 'required|string|min:3|max:50|alpha_num',
          'firstname' => 'required|string|min:2|max:100|alpha',
          'lastname' => 'required|string|min:2|max:100|alpha'
      ]);

      if (!$validation['valid']) {
          return $this->respondWithValidationErrors($validation['errors']);
      }

      $validatedData = $validation['data'];
      
      try {
          $uuid = Uuid::uuid4()->toString();
          $user = new User($uuid, $validatedData['username'], $validatedData['firstname'], $validatedData['lastname']);
          $this->userService->create($user);

          return $this->respondWithMessage("User Created Successfully", 201);
      } catch (\Exception $e) {
          $this->logger->error('Error creating user', ['message' => $e->getMessage()]);
          return $this->respondWithError($response, "Creation Failed", "Failed to create user", 500);
      }
  }

  
  public function show(Request $request, Response $response, $args): Response
  { 
    $this->setContext($request, $response, $args);

    $userId = $args['userId'];
    $data = $this->userService->findById($userId);

    if(!empty($data)){
      return $this->respondWithData($data );
    }

    return $this->respondWithError($response, "User Not Found", "The user you are looking for does not exist.", 404);
  }
  

}