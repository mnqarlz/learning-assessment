<?php 
namespace App\Domain\User\Service;

use App\Domain\User\Model\User;
use App\Domain\User\Repository\UserRepository;

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

  public function create(User $user){
    $this->userRepository->createUser($user);
  }

}