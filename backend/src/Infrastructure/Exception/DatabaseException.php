<?php

declare(strict_types=1);

namespace App\Infrastructure\Exception;
 
use PDOException;
 
class DatabaseException extends PDOException 
{
}
