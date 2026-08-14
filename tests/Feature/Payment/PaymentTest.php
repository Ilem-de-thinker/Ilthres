<?php

use App\Domains\Payment\Actions\RecordPayment;
use App\Domains\Payment\DTOs\TransactionData;
use App\Domains\Payment\Events\PaymentFailed;
use App\Domains\Payment\Events\PaymentReceived;
use App\Domains\Payment\Livewire\BillingHistory;
use App\Domains\Payment\Policies\TransactionPolicy;
use App\Models\Business;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;

uses()->group('payment');

beforeEach(function () {
    $this->user = User::factory()->create(['onboarding_completed' => true]);
    $this->business = Business::factory()->create(['user_id' => $this->user->id]);
    $this->subscription = Subscription::factory()->create(['business_id' => $this->business->id]);
    session(['current_business_id' => $this->business->id]);
    $this->actingAs($this->user);
});

describe('Record Payment', function () {
    it('records a successful payment', function () {
        Event::fake();

        $data = new TransactionData(
            businessId: $this->business->id,
            subscriptionId: $this->subscription->id,
            reference: 'ref-'.uniqid(),
            amount: 5000.00,
            currency: 'NGN',
            status: 'success',
            gateway: 'paystack',
            gatewayReference: 'gateway-ref',
            paidAt: now(),
        );

        $transaction = app(RecordPayment::class)($data);

        expect($transaction)->toBeInstanceOf(Transaction::class)
            ->and($transaction->status)->toBe('success')
            ->and((float) $transaction->amount)->toBe(5000.00);

        Event::assertDispatched(PaymentReceived::class);
    });

    it('records a failed payment', function () {
        Event::fake();

        $data = new TransactionData(
            businessId: $this->business->id,
            subscriptionId: $this->subscription->id,
            reference: 'ref-'.uniqid(),
            amount: 5000.00,
            currency: 'NGN',
            status: 'failed',
            gateway: 'paystack',
            gatewayReference: null,
            paidAt: null,
        );

        $transaction = app(RecordPayment::class)($data);

        expect($transaction->status)->toBe('failed');

        Event::assertDispatched(PaymentFailed::class);
    });
});

describe('Billing History', function () {
    it('renders billing page', function () {
        $this->get('/dashboard/billing')->assertSuccessful();
    });

    it('shows empty state', function () {
        Livewire::test(BillingHistory::class)
            ->assertSuccessful();
    });

    it('lists transactions', function () {
        Transaction::factory()->count(3)->create([
            'business_id' => $this->business->id,
            'subscription_id' => $this->subscription->id,
        ]);

        Livewire::test(BillingHistory::class)
            ->assertSuccessful();
    });

    it('shows transaction details', function () {
        Transaction::factory()->create([
            'business_id' => $this->business->id,
            'subscription_id' => $this->subscription->id,
            'reference' => 'TXN-12345',
            'amount' => 5000.00,
            'status' => 'success',
        ]);

        Livewire::test(BillingHistory::class)
            ->assertSee('TXN-12345')
            ->assertSee('5,000.00');
    });
});

describe('Transaction Policy', function () {
    it('allows owner to view transaction', function () {
        $transaction = Transaction::factory()->create([
            'business_id' => $this->business->id,
            'subscription_id' => $this->subscription->id,
        ]);

        expect((new TransactionPolicy)->view($this->user, $transaction))->toBeTrue();
    });

    it('denies non-owner from viewing transaction', function () {
        $otherBusiness = Business::factory()->create();
        $otherSubscription = Subscription::factory()->create(['business_id' => $otherBusiness->id]);
        $transaction = Transaction::factory()->create([
            'business_id' => $otherBusiness->id,
            'subscription_id' => $otherSubscription->id,
        ]);

        expect((new TransactionPolicy)->view($this->user, $transaction))->toBeFalse();
    });
});
