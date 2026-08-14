<?php

use App\Domains\Notifications\Actions\MarkAllAsRead;
use App\Domains\Notifications\Actions\MarkAsRead;
use App\Domains\Notifications\Actions\UpdatePreferences;
use App\Domains\Notifications\Livewire\NotificationBell;
use App\Domains\Notifications\Livewire\NotificationList;
use App\Domains\Notifications\Livewire\NotificationPreferences;
use App\Domains\Notifications\Notifications\ReviewReceived;
use App\Domains\Notifications\Notifications\SubscriptionExpiring;
use App\Domains\Notifications\Notifications\VerificationApproved;
use App\Models\Business;
use App\Models\Notification;
use App\Models\Subscription;
use App\Models\User;
use Livewire\Livewire;

uses()->group('notifications');

beforeEach(function () {
    $this->user = User::factory()->create(['onboarding_completed' => true]);
    $this->actingAs($this->user);
});

describe('Notification Bell', function () {
    it('renders with zero unread', function () {
        Livewire::test(NotificationBell::class)
            ->assertSuccessful();
    });

    it('shows unread count', function () {
        Notification::create([
            'id' => (string) str()->uuid(),
            'type' => 'test',
            'notifiable_type' => get_class($this->user),
            'notifiable_id' => $this->user->id,
            'data' => ['body' => 'Test'],
            'title' => 'Test Notification',
        ]);

        Livewire::test(NotificationBell::class)
            ->assertSee('Test Notification');
    });

    it('marks a notification as read', function () {
        $notification = Notification::create([
            'id' => (string) str()->uuid(),
            'type' => 'test',
            'notifiable_type' => get_class($this->user),
            'notifiable_id' => $this->user->id,
            'data' => ['body' => 'Test'],
            'title' => 'Test',
        ]);

        Livewire::test(NotificationBell::class)
            ->call('markAsRead', $notification->id)
            ->assertSuccessful();

        expect($notification->fresh()->read_at)->not->toBeNull();
    });

    it('marks all as read', function () {
        Notification::create([
            'id' => (string) str()->uuid(),
            'type' => 'test',
            'notifiable_type' => get_class($this->user),
            'notifiable_id' => $this->user->id,
            'data' => ['body' => 'Test 1'],
            'title' => 'Test 1',
        ]);
        Notification::create([
            'id' => (string) str()->uuid(),
            'type' => 'test',
            'notifiable_type' => get_class($this->user),
            'notifiable_id' => $this->user->id,
            'data' => ['body' => 'Test 2'],
            'title' => 'Test 2',
        ]);

        Livewire::test(NotificationBell::class)
            ->call('markAllAsRead')
            ->assertSuccessful();

        expect(Notification::whereNull('read_at')->count())->toBe(0);
    });
});

describe('Notification List', function () {
    it('renders notification list page', function () {
        $this->get('/dashboard/notifications')->assertSuccessful();
    });

    it('shows empty state', function () {
        Livewire::test(NotificationList::class)
            ->assertSee('No notifications');
    });

    it('lists notifications', function () {
        Notification::create([
            'id' => (string) str()->uuid(),
            'type' => 'test',
            'notifiable_type' => get_class($this->user),
            'notifiable_id' => $this->user->id,
            'data' => ['body' => 'Notification body'],
            'title' => 'Notification Title',
        ]);

        Livewire::test(NotificationList::class)
            ->assertSee('Notification Title');
    });

    it('filters by unread', function () {
        Notification::create([
            'id' => (string) str()->uuid(),
            'type' => 'test',
            'notifiable_type' => get_class($this->user),
            'notifiable_id' => $this->user->id,
            'data' => ['body' => 'Unread'],
            'title' => 'Unread Notification',
        ]);

        Livewire::test(NotificationList::class)
            ->set('filter', 'unread')
            ->assertSee('Unread Notification');
    });
});

describe('MarkAsRead Action', function () {
    it('marks notification as read', function () {
        $notification = Notification::create([
            'id' => (string) str()->uuid(),
            'type' => 'test',
            'notifiable_type' => get_class($this->user),
            'notifiable_id' => $this->user->id,
            'data' => ['body' => 'Test'],
            'title' => 'Test',
        ]);

        app(MarkAsRead::class)($notification);

        expect($notification->fresh()->read_at)->not->toBeNull();
    });
});

describe('MarkAllAsRead Action', function () {
    it('marks all unread notifications as read', function () {
        Notification::create([
            'id' => (string) str()->uuid(),
            'type' => 'test',
            'notifiable_type' => get_class($this->user),
            'notifiable_id' => $this->user->id,
            'data' => ['body' => 'Test 1'],
            'title' => 'Test 1',
        ]);
        Notification::create([
            'id' => (string) str()->uuid(),
            'type' => 'test',
            'notifiable_type' => get_class($this->user),
            'notifiable_id' => $this->user->id,
            'data' => ['body' => 'Test 2'],
            'title' => 'Test 2',
        ]);

        app(MarkAllAsRead::class)($this->user);

        expect(Notification::whereNull('read_at')->count())->toBe(0);
    });
});

describe('UpdatePreferences Action', function () {
    it('updates notification preferences', function () {
        app(UpdatePreferences::class)($this->user, [
            'email_reviews' => false,
            'email_subscription' => true,
            'email_marketing' => true,
        ]);

        $prefs = $this->user->fresh()->notification_preferences;

        expect($prefs['email_reviews'])->toBeFalse()
            ->and($prefs['email_marketing'])->toBeTrue();
    });
});

describe('Notification Preferences Page', function () {
    it('renders preferences page', function () {
        $this->get('/dashboard/notifications/preferences')->assertSuccessful();
    });

    it('saves preferences', function () {
        Livewire::test(NotificationPreferences::class)
            ->set('emailReviews', false)
            ->set('emailMarketing', true)
            ->call('save');

        $prefs = $this->user->fresh()->notification_preferences;

        expect($prefs['email_reviews'])->toBeFalse()
            ->and($prefs['email_marketing'])->toBeTrue();
    });
});

describe('Notification Classes', function () {
    it('ReviewReceived has database representation', function () {
        $business = Business::factory()->create();
        $notification = new ReviewReceived(
            business: $business,
            reviewerName: 'John Doe',
            rating: 5,
            comment: 'Great!',
        );

        $data = $notification->toDatabase($this->user);

        expect($data['title'])->toContain('5-star')
            ->and($data['body'])->toContain('John Doe');
    });

    it('SubscriptionExpiring has database representation', function () {
        $subscription = Subscription::factory()->create();
        $notification = new SubscriptionExpiring(subscription: $subscription);

        $data = $notification->toDatabase($this->user);

        expect($data['title'])->toContain('expiring');
    });

    it('VerificationApproved has database representation', function () {
        $business = Business::factory()->create(['name' => 'Verified Biz']);
        $notification = new VerificationApproved(business: $business);

        $data = $notification->toDatabase($this->user);

        expect($data['title'])->toContain('verified');
    });

    it('sends notifications via database channel', function () {
        $business = Business::factory()->create();

        $this->user->notify(new ReviewReceived(
            business: $business,
            reviewerName: 'Jane',
            rating: 4,
        ));

        expect(Notification::where('notifiable_id', $this->user->id)->count())->toBe(1);
    });
});
