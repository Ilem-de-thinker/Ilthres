<?php

namespace App\Domains\Notifications\Notifications;

use App\Models\Business;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerificationApproved extends Notification
{
    use Queueable;

    public function __construct(
        public Business $business,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("{$this->business->name} is now verified!")
            ->line("Congratulations! Your business {$this->business->name} has been verified.")
            ->action('View business', route('business.profile', $this->business));
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Business verified',
            'body' => "{$this->business->name} is now verified!",
            'business_id' => $this->business->id,
        ];
    }
}
