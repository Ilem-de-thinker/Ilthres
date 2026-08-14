<?php

namespace App\Domains\Notifications\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PricingReEnabled extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pricing has been re-enabled on Ilthres')
            ->line('Pricing and paid features have been re-enabled on the platform.')
            ->line('To continue using premium features, please ensure your subscription is active.')
            ->line('If your subscription has expired, you can renew it from your dashboard.')
            ->action('Go to Dashboard', route('dashboard'));
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Pricing re-enabled',
            'body' => 'Pricing has been re-enabled on the platform. Please ensure your subscription is active to continue using premium features.',
        ];
    }
}
