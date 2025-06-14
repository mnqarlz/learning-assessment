<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\User\Model\User; 
use App\Domain\User\Exception\UserNotFoundException;

interface UserRepository
{
    /**
     * @return User[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return User
     * @throws UserNotFoundException
     */
    public function findById(String $id): ?User;
    
    /**
     * @param int $id
     * @return User
     * @throws UserNotFoundException
     */
    public function findByEmail(String $email): ?User;

    public function createUser(User $user): void;
}
