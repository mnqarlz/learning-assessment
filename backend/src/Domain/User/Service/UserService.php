<?php
namespace App\Domain\User\Service;

use App\Domain\User\Model\User; 
use App\Infrastructure\Service\MailerService;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\Authentication\Exception\InvalidCredentialsException;

class UserService
{
    protected UserRepository $userRepository;
    protected MailerService $mailerService;

    public function __construct(UserRepository $userRepository, MailerService $mailerService)
    {
        $this->userRepository = $userRepository;
        $this->mailerService = $mailerService;
    }

    public function getUserList(): array
    {
        return $this->userRepository->findAll();
    }

    public function findById(string $userId): ?User
    {
        return $this->userRepository->findById($userId);
    }

    public function authenticateUser(string $email, string $password): User
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            throw new UserNotFoundException('email', $email);
        }

        if (!password_verify($password, $user->getPasswordHash())) {
            throw new InvalidCredentialsException();
        }

        return $user;
    }

    public function create(User $user)
    {
        $this->userRepository->createUser($user);
    }

    public function sendWelcomeEmailWithPassword(User $user, string $plainPassword)
    {
        $this->mailerService->sendWelcomeEmailWithPassword($user, $plainPassword);
    }

    public function sendPasswordResetEmail(User $user, string $newPassword)
    {
        $this->mailerService->sendPasswordResetEmail($user, $newPassword);
    }
}
