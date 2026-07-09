<?php

namespace App\Domains\Administration\Livewire;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Admin user management list.
 *
 * Displays all registered users with their details and admin status.
 * Supports search and toggling admin access.
 */
#[Layout('layouts.admin')]
class UserManager extends Component
{
    use WithPagination;

    public string $search = '';

    /**
     * Toggle the is_admin flag for a given user.
     */
    public function toggleAdmin(int $userId): void
    {
        $user = User::findOrFail($userId);

        // Prevent self-demotion.
        if ($user->id === auth()->id()) {
            session()->flash('error', 'You cannot change your own admin status.');
            return;
        }

        $user->update(['is_admin' => ! $user->is_admin]);

        session()->flash('success', "Admin status updated for {$user->name}.");
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            }))
            ->latest()
            ->paginate(20);

        return view('livewire.administration.user-manager', [
            'users' => $users,
        ]);
    }
}
