<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    public function __construct(
        public string $token,
    ) {}

    public function via(): array
    {
        return ["mail"];
    }

    public function toMail($notifiable): MailMessage
    {
        $resetUrl = url(route("password.reset", [
            "token" => $this->token,
            "email" => $notifiable->getEmailForPasswordReset(),
        ], false));

        return new MailMessage()
            ->subject(__("email.auth.reset_password.subject"))
            ->view("emails.auth.reset-password", [
                "resetUrl" => $resetUrl,
                "user" => $notifiable,
            ]);
    }
}
