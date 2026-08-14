<?php

use App\Domains\Notifications\Notifications\PricingReEnabled;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

it('sends pricing re-enabled notification to user', function () {
    Notification::fake();

    $user = User::factory()->create();

    $user->notify(new PricingReEnabled);

    Notification::assertSentTo($user, PricingReEnabled::class);
});

it('notification uses database and mail channels', function () {
    $notification = new PricingReEnabled;
    $notifiable = new stdClass;

    expect($notification->via($notifiable))->toBe(['database', 'mail']);
});

it('creates a database notification with correct data', function () {
    $user = User::factory()->create();

    $user->notify(new PricingReEnabled);

    $notification = $user->notifications()->latest()->first();
    expect($notification)->not->toBeNull();
    expect($notification->data['title'])->toBe('Pricing re-enabled');
    expect($notification->data['body'])->toContain('Pricing has been re-enabled');
});

it('sends mail notification with correct subject', function () {
    $user = User::factory()->create();
    $notification = new PricingReEnabled;

    $mail = $notification->toMail($user);

    expect($mail->subject)->toBe('Pricing has been re-enabled on Ilthres');
});
