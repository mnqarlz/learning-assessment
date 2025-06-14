<?php 
namespace App\Infrastructure\Persistence\MySql;

use PDO;
use PDOException;  
use App\Infrastructure\Database\StudentDatabase;
use App\Infrastructure\Exception\DatabaseException;

class MySqlStudentRepository {

  private PDO $pdo;

  public function __construct(StudentDatabase $sb)
  {
      $this->pdo = $sb->getPdo();
  }

  public function findAll(): array
  {
      try {
          $stmt = $this->pdo->query("SELECT * FROM student");
          return $stmt->fetchAll();
      } catch (PDOException $e) {
          throw new DatabaseException("Error fetching all users", (int)$e->getCode(), $e);
      }
  }
}