<?php
declare(strict_types=1);

namespace App\Infrastructure\Controller;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid; 
use App\Domain\Role\Model\Role;
use App\Domain\User\Model\User;
use App\Domain\User\Service\UserService;
use App\Application\Controller\Controller;
use App\Domain\User\Model\UserInformation;
use App\Infrastructure\Util\PasswordGenerator;
use Psr\Http\Message\ResponseInterface as Response;
use Illuminate\Validation\Factory as ValidationFactory;
use Psr\Http\Message\ServerRequestInterface as Request; 

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
      $this->setContext($request, response: $response);
   
      $data = $request->getParsedBody();
       
      $validation = $this->validateRequest($data, [
          'email' => 'required|string|min:3|max:50|email',
          'firstName' => 'required|string|min:2|max:100|alpha',
          'lastName' => 'required|string|min:2|max:100|alpha',
          'role_id' => 'required|integer',
          'contact_number' => 'required|integer|min:10'
      ]);

      if (!$validation['valid']) {
          return $this->respondWithValidationErrors($validation['errors']);
      }

      $validatedData = $validation['data'];
      
      try {
          $uuid = Uuid::uuid4()->toString();
          $userInformation = new UserInformation($validatedData['firstName'], $validatedData['lastName'], (string) $validatedData['contact_number']);
          $userRole = new Role($validatedData['role_id']);
          $user = new User($uuid, $validatedData['email'],  $userRole,Carbon::now(), Carbon::now(), $userInformation );
         
         // Generate a random password
          $randomPassword = PasswordGenerator::generateRandomPassword(12);

          // Hash it
          $hashedPassword = PasswordGenerator::hashPassword($randomPassword);
          $user->setPasswordHash($hashedPassword);
          // $this->userService->create($user);

          $this->userService->sendWelcomeEmailWithPassword($user, $randomPassword);
          $this->logger->info('User created successfully', ['user_id' => $user->getId()]);

          return $this->respondWithMessage("User Created Successfully", 201);
      } catch (\Exception $e) {
          $this->logger->error('Error creating user', ['message' => $e->getMessage()]);
          return $this->respondWithError($response, "Creation Failed", "Failed to create user $e", 500);
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