<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends VerifyEmailBase
{
    protected function buildMailMessage($url): MailMessage
    {
        return new MailMessage()
            ->subject(__("email.auth.verify_email.subject"))
            ->view("emails.auth.verify-email", [
                "verificationUrl" => $url,
                "user" => request()->user(),
            ]);
    }

    protected function verificationUrl($notifiable): string
    {
        return URL::temporarySignedRoute(
            "verification.verify",
            Carbon::now()->addMinutes(Config::get("auth.verification.expire", 60)),
            [
                "id" => $notifiable->getKey(),
                "hash" => sha1($notifiable->getEmailForVerification()),
            ],
        );
    }
}
