<?php
namespace App\Infrastructure\Service;
 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Domain\User\Model\User;

class MailerService
{
    private PHPMailer $mailer;

    public function __construct(PHPMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendWelcomeEmailWithPassword(User $user, string $plainPassword): void
    {
        $subject = 'Welcome to Our Platform - Your Account Details';
        $body = $this->generateWelcomeEmailWithPasswordBody($user, $plainPassword);
        
        $this->sendEmail($user, $subject, $body);
    }

    public function sendPasswordResetEmail(User $user, string $newPassword): void
    {
        $subject = 'Your Password Has Been Reset';
        $body = $this->generatePasswordResetEmailBody($user, $newPassword);

        $this->sendEmail($user, $subject, $body);
    }

    private function sendEmail(User $user, string $subject, string $body): void
    {
        try {
            $this->mailer->addAddress($user->getEmail(), $user->getUserInformation()->getFirstName());
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;
            $this->mailer->send();
        } catch (Exception $e) {
            throw new \Exception("Failed to send email: " . $e->getMessage());
        }
    }

    private function generateWelcomeEmailWithPasswordBody(User $user, string $plainPassword): string
    {
        $appName = getenv('APP_NAME');
        $appUrl = getenv('APP_URL');
        $fullName = $user->getUserInformation()->getFirstName() . ' ' . $user->getUserInformation()->getLastName();

        return "
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Welcome to {$appName}</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #f4f4f4; padding: 20px; text-align: center; }
                .content { padding: 20px; }
                .credentials { background-color: #f9f9f9; padding: 15px; border-left: 4px solid #872E4A; margin: 20px 0; }
                .footer { background-color: #f4f4f4; padding: 20px; text-align: center; font-size: 12px; color: #666; }
                .button { display: inline-block; padding: 10px 20px; background-color: #872E4A; color: white; text-align:center; text-decoration: none; border-radius: 5px; }
                .warning { color: #EF4444; font-weight: bold; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Welcome to {$appName}!</h1>
                </div>
                
                <div class='content'>
                    <h2>Hello {$fullName},</h2>
                    <p>Your account has been successfully created! We're excited to have you on board.</p>
                    
                    <div class='credentials'>
                        <h3>Your Login Credentials:</h3>
                        <p><strong>Email:</strong> {$user->getEmail()}</p>
                        <p><strong>Password:</strong> {$plainPassword}</p>
                    </div>

                    <p class='warning'>Important Security Notice:</p>
                    <ul>
                        <li>Please change your password after your first login</li>
                        <li>Do not share your credentials with anyone</li>
                        <li>Keep this email secure or delete it after changing your password</li>
                    </ul>
                    
                    <p>
                        <a href='{$appUrl}/login' class='button'>Login to Your Account</a>
                    </p>
                    
                    <p>If you have any questions or need assistance, please don't hesitate to contact our support team.</p>
                </div>

                <div class='footer'>
                    <p>This is an automated message. Please do not reply to this email.</p>
                    <p>&copy; " . date('Y') . " {$appName}. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }

    private function generatePasswordResetEmailBody(User $user, string $newPassword): string
    {
        $appName = getenv('APP_NAME');
        $appUrl = getenv('APP_URL');
        $fullName = $user->getUserInformation()->getFirstName() . ' ' . $user->getUserInformation()->getLastName();

        return "
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Password Reset - {$appName}</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #f4f4f4; padding: 20px; text-align: center; }
                .content { padding: 20px; }
                .credentials { background-color: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 20px 0; }
                .footer { background-color: #f4f4f4; padding: 20px; text-align: center; font-size: 12px; color: #666; }
                .button { display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; }
                .warning { color: #d63384; font-weight: bold; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Password Reset</h1>
                </div>

                <div class='content'>
                    <h2>Hello {$fullName},</h2>
                    <p>Your password has been reset as requested.</p>

                    <div class='credentials'>
                        <h3>Your New Password:</h3>
                        <p><strong>Password:</strong> {$newPassword}</p>
                    </div>

                    <p class='warning'>Important:</p>
                    <ul>
                        <li>Please login and change this password immediately</li>
                        <li>If you did not request this password reset, contact support immediately</li>
                    </ul>

                    <p>
                        <a href='{$appUrl}/login' class='button'>Login Now</a>
                    </p>
                </div>

                <div class='footer'>
                    <p>This is an automated message. Please do not reply to this email.</p>
                    <p>&copy; " . date('Y') . " {$appName}. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
}
