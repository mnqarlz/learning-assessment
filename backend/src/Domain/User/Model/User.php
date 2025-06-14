<?php
declare(strict_types=1);

namespace App\Domain\User\Model;

use JsonSerializable;

class User implements JsonSerializable
{
    private ?string $id;

    private string $email;

    private string $firstName;

    private string $lastName;

    private string $passwordHash;
    
    public function __construct(?string $id, string $email, string $firstName, string $lastName)
    {
        $this->id = $id;
        $this->email = strtolower($email);
        $this->firstName = ucfirst($firstName);
        $this->lastName = ucfirst($lastName); 
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of firstName
     */ 
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set the value of firstName
     *
     * @return  self
     */ 
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get the value of lastName
     */ 
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set the value of lastName
     *
     * @return  self
     */ 
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get the value of passwordHash
     */ 
    public function getPasswordHash()
    {
        return $this->passwordHash;
    }

    /**
     * Set the value of passwordHash
     *
     * @return  self
     */ 
    public function setPasswordHash($passwordHash)
    {
        $this->passwordHash = $passwordHash;

        return $this;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
        ];
    }
}
