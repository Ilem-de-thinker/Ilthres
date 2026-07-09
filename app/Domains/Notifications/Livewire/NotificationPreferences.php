<?php

namespace App\Domains\Notifications\Livewire;

use Livewire\Component;

class NotificationPreferences extends Component
{
    public bool $emailReviews = true;
    public bool $emailSubscription = true;
    public bool $emailMarketing = false;

    public function mount(): void
    {
        $user = auth()->user();
        $prefs = $user->notification_preferences ?? [];

        $this->emailReviews = $prefs['email_reviews'] ?? true;
        $this->emailSubscription = $prefs['email_subscription'] ?? true;
        $this->emailMarketing = $prefs['email_marketing'] ?? false;
    }

    public function save(): void
    {
        auth()->user()->update([
            'notification_preferences' => [
                'email_reviews' => $this->emailReviews,
                'email_subscription' => $this->emailSubscription,
                'email_marketing' => $this->emailMarketing,
            ],
        ]);

        session()->flash('saved', true);
    }

    public function render()
    {
        return view('livewire.notifications.notification-preferences');
    }
}
