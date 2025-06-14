<?php 
namespace App\Infrastructure\Persistence\MySql;

use PDO;
use DateTime;
use PDOException;
use App\Domain\Role\Model\Role;
use App\Domain\User\Model\User;  
use App\Domain\User\Model\UserInformation;
use App\Domain\User\Repository\UserRepository;
use App\Infrastructure\Exception\DatabaseException;

class MySqlUserRepository implements UserRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        try {
            $sql = "
                SELECT 
                    u.*, 
                    ui.first_name, ui.last_name, ui.contact_number, 
                    r.id AS role_id, r.role_name 
                FROM users u
                LEFT JOIN user_information ui ON ui.user_id = u.id
                LEFT JOIN role r ON r.id = u.role_id
            ";
    
            $stmt = $this->pdo->query($sql);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            $users = [];
            foreach ($results as $result) {
                $userInformation = new UserInformation(
                    $result['first_name'] ?? '',
                    $result['last_name'] ?? '',
                    $result['contact_number'] ?? ''
                );
    
                $role = new Role(
                    $result['role_id'] ?? null,
                    $result['role_name'] ?? ''
                );
    
                $user = new User(
                    $result['id'],
                    $result['email'],
                    $role,
                    new DateTime($result['created_at']),
                    new DateTime($result['updated_at']),
                    $userInformation
                );
    
                $user->setPasswordHash($result['password'] ?? '');
    
                $users[] = $user;
            }
    
            return $users;
        } catch (PDOException $e) {
            throw new DatabaseException("Error fetching all users with user information", (int)$e->getCode(), $e);
        }
    }
    

    public function findById(string $id): ?User
    {
        try {
            $sql = "
                SELECT u.*, 
                       ui.first_name, ui.last_name, ui.contact_number,
                       r.id AS role_id, r.role_name
                FROM users u
                LEFT JOIN user_information ui ON ui.user_id = u.id
                LEFT JOIN role r ON r.id = u.role_id
                WHERE u.id = :id
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $id);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $userInformation = new UserInformation(
                    $result['first_name'] ?? '',
                    $result['last_name'] ?? '',
                    $result['contact_number'] ?? ''
                );
    
                $role = new Role(
                    $result['role_id'] ?? null,
                    $result['role_name'] ?? ''
                );
    
                $user = new User(
                    $result['id'],
                    $result['email'],
                    $role,
                    new DateTime($result['created_at']),
                    new DateTime($result['updated_at']),
                    $userInformation
                );

                $user->setPasswordHash($result['password'] ?? '');

                return $user;
            }

            return null;
        } catch (PDOException $e) {
            throw new DatabaseException("Error fetching user by ID with user information", (int)$e->getCode(), $e);
        }
    }

    public function findByEmail(string $email): ?User
    {
        try {
            $sql = "
                SELECT u.*, 
                       ui.first_name, ui.last_name, ui.contact_number,
                       r.id AS role_id, r.role_name
                FROM users u
                LEFT JOIN user_information ui ON ui.user_id = u.id
                LEFT JOIN role r ON r.id = u.role_id
                WHERE u.email = :email
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":email", $email);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $userInformation = new UserInformation(
                    $result['first_name'] ?? '',
                    $result['last_name'] ?? '',
                    $result['contact_number'] ?? ''
                );
    
                $role = new Role(
                    $result['role_id'] ?? null,
                    $result['role_name'] ?? ''
                );
    
                $user = new User(
                    $result['id'],
                    $result['email'],
                    $role,
                    new DateTime($result['created_at']),
                    new DateTime($result['updated_at']),
                    $userInformation
                );

                $user->setPasswordHash($result['password'] ?? '');

                return $user;
            }

            return null;
        } catch (PDOException $e) {
            throw new DatabaseException("Error fetching user by email with user information", (int)$e->getCode(), $e);
        }
    }

    public function createUser(User $user): void
    {
        try {
            $this->pdo->beginTransaction();

            // Insert into users table
            $sqlUser = "INSERT INTO users (id, email, password, role_id, created_at, updated_at)
                        VALUES (:id, :email, :password, :role_id, :created_at, :updated_at)";

            $stmtUser = $this->pdo->prepare($sqlUser);
            $stmtUser->bindValue(':id', $user->getId());
            $stmtUser->bindValue(':email', $user->getEmail());
            $stmtUser->bindValue(':password', $user->getPasswordHash());
            $stmtUser->bindValue(':role_id', $user->getRole()->getId());
            $stmtUser->bindValue(':created_at', $user->getCreatedAt()->format('Y-m-d H:i:s'));
            $stmtUser->bindValue(':updated_at', $user->getUpdatedAt()->format('Y-m-d H:i:s'));
            $stmtUser->execute();

            // Insert into user_information table
            $sqlInfo = "INSERT INTO user_information (first_name, last_name, contact_number, user_id)
                        VALUES ( :first_name, :last_name, :contact_number, :user_id)";

            $stmtInfo = $this->pdo->prepare($sqlInfo);
            $stmtInfo->bindValue(':first_name', $user->getUserInformation()->getFirstName());
            $stmtInfo->bindValue(':last_name', $user->getUserInformation()->getLastName());
            $stmtInfo->bindValue(':contact_number', $user->getUserInformation()->getContactNumber());
            $stmtInfo->bindValue(':user_id', $user->getId());
            $stmtInfo->execute();

            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new DatabaseException("Error creating user with user information", (int)$e->getCode(), $e);
        }
    }
}
