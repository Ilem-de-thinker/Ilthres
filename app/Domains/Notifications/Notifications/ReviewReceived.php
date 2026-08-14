<?php

namespace App\Domains\Notifications\Notifications;

use App\Models\Business;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReviewReceived extends Notification
{
    use Queueable;

    public function __construct(
        public Business $business,
        public string $reviewerName,
        public int $rating,
        public ?string $comment = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("New {$this->rating}-star review for {$this->business->name}")
            ->line("{$this->reviewerName} left a {$this->rating}-star review.")
            ->when($this->comment, fn ($m) => $m->line("\"{$this->comment}\""))
            ->action('View review', route('dashboard'));
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => "New {$this->rating}-star review",
            'body' => "{$this->reviewerName} reviewed {$this->business->name}.".($this->comment ? " \"{$this->comment}\"" : ''),
            'business_id' => $this->business->id,
        ];
    }
}
