<?php

namespace App\Domains\Authentication\Livewire;

use Livewire\Component;

/**
 * Post-registration choice page.
 *
 * Presents two options after a new user registers:
 * 1. Link an existing business to their account.
 * 2. Create a new business (enters the onboarding wizard).
 */
class GetStarted extends Component
{
    public function linkBusiness(): void
    {
        $this->redirect(route('link-business'), navigate: true);
    }

    public function createBusiness(): void
    {
        $this->redirect(route('onboarding'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.get-started');
    }
}
