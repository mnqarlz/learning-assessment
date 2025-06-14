<?php 
namespace App\Infrastructure\Persistence;

use PDO;
use PDOException;
use App\Domain\User\Model\User;
use App\Domain\User\Repository\UserRepository;
use App\Exceptions\DatabaseException;

class MySqlUserRepository implements UserRepository {

    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM users");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new DatabaseException("Error fetching all users", (int)$e->getCode(), $e);
        }
    }

    public function findById(string $id): ?User
    {
        try {
            $sql = "SELECT * FROM users WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $id);
            $stmt->execute();

            $result = $stmt->fetch();

            if ($result) {
                return new User($result['id'], $result['email'], $result['firstname'], $result['lastname']);
            }

            return null;
        } catch (PDOException $e) {
            throw new DatabaseException("Error fetching user by ID", (int)$e->getCode(), $e);
        }
    }

    public function findByEmail(string $email): ?User
    {
        try {
            $sql = "SELECT * FROM users WHERE email = :email";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":email", $email);
            $stmt->execute();

            $result = $stmt->fetch();

            if ($result) {

                $user = new User(
                    (int)$result['id'],
                    $result['email'],
                    $result['firstname'],
                    $result['lastname'],
                );

                $user->setPasswordHash($result['password']);
                return $user; 
            }

            return null;
        } catch (PDOException $e) {
            throw new DatabaseException("Error fetching user by email", (int)$e->getCode(), $e);
        }
    }


    public function createUser(User $user): void
    {
        try {
            $sql = "INSERT INTO users (id, email, firstname, lastname)
                    VALUES (:id, :email, :firstname, :lastname)";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(':id', $user->getId());
            $stmt->bindValue(':email', $user->getEmail());
            $stmt->bindValue(':firstname', $user->getFirstName());
            $stmt->bindValue(':lastname', $user->getLastName());

            $stmt->execute();
        } catch (PDOException $e) {
            throw new DatabaseException("Error creating user", (int)$e->getCode(), $e);
        }
    }
}
