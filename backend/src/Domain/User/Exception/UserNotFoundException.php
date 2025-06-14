<?php

declare(strict_types=1);

namespace App\Domain\User\Exception;

use App\Domain\DomainExceptions\DomainException;

class UserNotFoundException extends DomainException
{
    public function __construct(string $identifierType = 'identifier', string $value = '')
    {
        $message = "User with {$identifierType} '{$value}' was not found.";
        parent::__construct($message);
    }
}
