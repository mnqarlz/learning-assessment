<?php
declare(strict_types=1);

namespace App\Domain\User\Model;

use DateTime;
use JsonSerializable; 
use App\Domain\Role\Model\Role;

class User implements JsonSerializable
{
    private ?string $id;
    private string $email;
    private string $passwordHash;
    private DateTime $createdAt;
    private DateTime $updatedAt;
    private Role $role;
    private UserInformation $userInformation;

    public function __construct(
        ?string $id,
        string $email,
        Role $role,
        DateTime $createdAt,
        DateTime $updatedAt,
        UserInformation $userInformation
    ) {
        $this->id = $id;
        $this->email = strtolower($email);
        $this->role = $role;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->userInformation = $userInformation;
    }

    public function getId(): ?string
    {
        return $this->id;
    }
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
    public function setEmail(string $email): self
    {
        $this->email = strtolower($email);
        return $this;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }
    public function setPasswordHash(string $passwordHash): self
    {
        $this->passwordHash = $passwordHash;
        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }
    public function setUpdatedAt(DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getUserInformation(): UserInformation
    {
        return $this->userInformation;
    }
    public function setUserInformation(UserInformation $userInformation): self
    {
        $this->userInformation = $userInformation;
        return $this;
    }

    /**
     * Get the value of role
     */ 
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set the value of role
     *
     * @return  self
     */ 
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
            'user_information' => [
                'first_name' => $this->userInformation->getFirstName(),
                'last_name' => $this->userInformation->getLastName(),
                'contact_number' => $this->userInformation->getContactNumber(),
            ],
            'role' => $this->getRole()->getRoleName(),
        ];
    }
}
