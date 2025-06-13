<?php 
namespace App\Infrastructure\Persistence;

use PDO;
use App\Domain\User\Model\User;
use App\Domain\User\Repository\UserRepository;
 

class MySqlUserRepository implements UserRepository {

  private PDO $pdo;

  public function __construct(PDO $pdo)
  {
      $this->pdo = $pdo;
  }


  public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM users");
        return $stmt->fetchAll();
    }
  
    public function findById(string $id): ?User {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
         
        $result = $stmt->fetch();
         
        if ($result) {
            return new User($result['id'], $result['username'], $result['firstname'], $result['lastname']);
        }
         
        return null;
    }
    
    public function createUser(User $user): void
    {
        $sql = "INSERT INTO users (id, username, firstname, lastname)
                VALUES (:id, :username, :firstname, :lastname)";
    
        $stmt = $this->pdo->prepare($sql);
    
        $stmt->bindValue(':id', $user->getId());
        $stmt->bindValue(':username', $user->getUsername());
        $stmt->bindValue(':firstname', $user->getFirstName());
        $stmt->bindValue(':lastname', $user->getLastName());
    
        $stmt->execute();
    }
    
}