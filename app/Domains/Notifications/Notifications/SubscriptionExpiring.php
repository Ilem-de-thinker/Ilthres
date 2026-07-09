<?php

namespace App\Domains\Notifications\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionExpiring extends Notification
{
    use Queueable;

    public function __construct(
        public Subscription $subscription,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $plan = $this->subscription->plan;

        return (new MailMessage)
            ->subject("Your {$plan->name} plan expires soon")
            ->line("Your {$plan->name} subscription for {$this->subscription->business->name} ends on {$this->subscription->ends_at->format('M j, Y')}.")
            ->action('Renew now', route('dashboard.subscription'));
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Subscription expiring soon',
            'body' => "Your {$this->subscription->plan->name} plan ends on {$this->subscription->ends_at->format('M j, Y')}.",
            'subscription_id' => $this->subscription->id,
            'business_id' => $this->subscription->business_id,
        ];
    }
}
