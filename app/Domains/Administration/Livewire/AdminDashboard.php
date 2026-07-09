<?php

namespace App\Domains\Administration\Livewire;

use App\Models\Business;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Admin dashboard showing platform-wide statistics with revenue,
 * charts, subscription breakdown, and recent transactions.
 */
#[Layout('layouts.admin')]
class AdminDashboard extends Component
{
    public int $totalUsers = 0;
    public int $usersToday = 0;
    public int $usersThisMonth = 0;
    public int $totalBusinesses = 0;
    public int $businessesToday = 0;
    public float $totalRevenue = 0;
    public float $revenueToday = 0;
    public float $revenueThisMonth = 0;
    public int $pendingBusinesses = 0;
    public int $activeSubscriptions = 0;
    public int $trialSubscriptions = 0;
    public int $expiredSubscriptions = 0;
    public int $cancelledSubscriptions = 0;

    public function mount(): void
    {
        $now = now();
        $todayStart = $now->copy()->startOfDay();
        $monthStart = $now->copy()->startOfMonth();

        // Users.
        $this->totalUsers = User::count();
        $this->usersToday = User::where('created_at', '>=', $todayStart)->count();
        $this->usersThisMonth = User::where('created_at', '>=', $monthStart)->count();

        // Businesses.
        $this->totalBusinesses = Business::count();
        $this->businessesToday = Business::where('created_at', '>=', $todayStart)->count();
        $this->pendingBusinesses = Business::where('is_active', false)->count();

        // Revenue.
        $this->totalRevenue = (float) Transaction::where('status', 'success')->sum('amount');
        $this->revenueToday = (float) Transaction::where('status', 'success')->where('paid_at', '>=', $todayStart)->sum('amount');
        $this->revenueThisMonth = (float) Transaction::where('status', 'success')->where('paid_at', '>=', $monthStart)->sum('amount');

        // Subscriptions.
        $this->activeSubscriptions = Subscription::where('status', 'active')->count();
        $this->trialSubscriptions = Subscription::where('status', 'trial')->count();
        $this->expiredSubscriptions = Subscription::where('status', 'expired')->count();
        $this->cancelledSubscriptions = Subscription::where('status', 'cancelled')->count();
    }

    public function render()
    {
        $now = now();

        // Revenue chart — daily revenue for last 30 days.
        $revenueChart = Transaction::where('status', 'success')
            ->where('paid_at', '>=', $now->copy()->subDays(30))
            ->get()
            ->groupBy(fn ($t) => $t->paid_at->format('Y-m-d'))
            ->map(fn ($txns) => (float) $txns->sum('amount'));

        $revenueLabels = collect();
        $revenueValues = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i)->format('Y-m-d');
            $revenueLabels->push($now->copy()->subDays($i)->format('M j'));
            $revenueValues->push((int) ($revenueChart[$date] ?? 0));
        }

        // User growth chart — daily signups for last 30 days.
        $userGrowth = User::where('created_at', '>=', $now->copy()->subDays(30))
            ->get()
            ->groupBy(fn ($u) => $u->created_at->format('Y-m-d'))
            ->map(fn ($users) => $users->count());

        $userLabels = collect();
        $userValues = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i)->format('Y-m-d');
            $userLabels->push($now->copy()->subDays($i)->format('M j'));
            $userValues->push($userGrowth[$date] ?? 0);
        }

        // Recent transactions.
        $recentTransactions = Transaction::with('business')
            ->latest('paid_at')
            ->take(10)
            ->get();

        // Recent subscriptions.
        $recentSubscriptions = Subscription::with(['business', 'plan'])
            ->latest('created_at')
            ->take(10)
            ->get();

        // Recent users.
        $recentUsers = User::latest()->take(5)->get();

        return view('livewire.administration.admin-dashboard', [
            'revenueLabels' => $revenueLabels,
            'revenueValues' => $revenueValues,
            'revenueMax' => max(1, $revenueValues->max()),
            'userLabels' => $userLabels,
            'userValues' => $userValues,
            'userMax' => max(1, $userValues->max()),
            'recentTransactions' => $recentTransactions,
            'recentSubscriptions' => $recentSubscriptions,
            'recentUsers' => $recentUsers,
        ]);
    }
}
