<?php

declare(strict_types=1);

namespace App\Exceptions;

class UserNotFoundException extends DomainException 
{
  public $message = 'The user you requested does not exist.';

}
