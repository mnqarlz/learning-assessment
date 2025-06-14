<?php 
namespace App\Domain\User\Service;

use App\Domain\User\Model\User; 
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\Authentication\Exception\InvalidCredentialsException;

class UserService {

  protected UserRepository $userRepository;
  public function __construct(UserRepository $userRepository) {
    $this->userRepository = $userRepository;
  }
  
  public function getUserList(): Array {
    return $this->userRepository->findAll();
  }

  public function findById(String $userId): ?User {
    return $this->userRepository->findById($userId);
  }

  public function authenticateUser(string $username, string $password): User
    {
        $user = $this->userRepository->findByUsername($username);

        if (!$user) {
            throw new UserNotFoundException('username', $username);
        }

        if (!password_verify($password, $user->getPasswordHash())) {
            throw new InvalidCredentialsException();
        }

        return $user;
    }

  public function create(User $user){
    $this->userRepository->createUser($user);
  }

}