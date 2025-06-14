<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\Role\Model\Role;
 

interface RoleRepository
{
    /**
     * @return Role[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Role
     * @throws  
     */
    public function findById(String $id): ?User;
     

    public function createRole(Role $role): void;
}
