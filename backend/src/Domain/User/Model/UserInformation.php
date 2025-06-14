<?php
namespace App\Domain\User\Model;

class UserInformation
{
    private string $firstName;
    private string $lastName;
    private string $contactNumber;

    public function __construct(string $firstName, string $lastName, string $contactNumber)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->contactNumber = $contactNumber;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getContactNumber(): string
    {
        return $this->contactNumber;
    }
    public function setContactNumber(string $contactNumber): self
    {
        $this->contactNumber = $contactNumber;
        return $this;
    }
}
