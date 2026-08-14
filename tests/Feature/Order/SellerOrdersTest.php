<?php

use App\Domains\Order\Livewire\SellerOrders;
use App\Models\Business;
use App\Models\Category;
use App\Models\Order;
use App\Models\Theme;
use App\Models\User;
use Livewire\Livewire;

uses()->group('orders');

beforeEach(function () {
    $this->user = User::factory()->create(['onboarding_completed' => true]);
    $this->actingAs($this->user);

    $this->category = Category::factory()->create(['name' => 'Fashion']);
    $this->theme = Theme::factory()->create();

    $this->business = Business::factory()->create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
        'type' => Business::TYPE_PRODUCT_STORE,
        'website_url' => null, // Hosted business
    ]);

    // Set current business in session
    session(['current_business_id' => $this->business->id]);
});

describe('Seller Orders Page', function () {
    it('renders dashboard orders page successfully', function () {
        $this->get('/dashboard/orders')->assertSuccessful();
    });

    it('lists orders belonging to the business', function () {
        $order1 = Order::create([
            'business_id' => $this->business->id,
            'order_number' => 'ORD-001',
            'type' => 'checkout',
            'status' => 'pending',
            'buyer_name' => 'John Doe',
            'buyer_email' => 'john@example.com',
            'subtotal' => 1000.00,
            'total' => 1000.00,
            'currency' => 'NGN',
        ]);

        $otherBusiness = Business::factory()->create([
            'user_id' => User::factory()->create()->id,
            'category_id' => $this->category->id,
            'type' => Business::TYPE_PRODUCT_STORE,
        ]);

        $order2 = Order::create([
            'business_id' => $otherBusiness->id,
            'order_number' => 'ORD-002',
            'type' => 'checkout',
            'status' => 'pending',
            'buyer_name' => 'Jane Smith',
            'subtotal' => 2000.00,
            'total' => 2000.00,
            'currency' => 'NGN',
        ]);

        Livewire::test(SellerOrders::class)
            ->assertSee('ORD-001')
            ->assertSee('John Doe')
            ->assertDontSee('ORD-002')
            ->assertDontSee('Jane Smith');
    });

    it('can view order details', function () {
        $order = Order::create([
            'business_id' => $this->business->id,
            'order_number' => 'ORD-001',
            'type' => 'checkout',
            'status' => 'pending',
            'buyer_name' => 'John Doe',
            'buyer_email' => 'john@example.com',
            'buyer_phone' => '1234567890',
            'buyer_message' => 'Deliver quickly',
            'subtotal' => 1000.00,
            'total' => 1000.00,
            'currency' => 'NGN',
        ]);

        Livewire::test(SellerOrders::class)
            ->call('viewingOrder', $order->id)
            ->assertSet('viewingOrderId', $order->id)
            ->assertSee('ORD-001')
            ->assertSee('Deliver quickly')
            ->call('closeOrder')
            ->assertSet('viewingOrderId', null);
    });

    it('can update order status', function () {
        $order = Order::create([
            'business_id' => $this->business->id,
            'order_number' => 'ORD-001',
            'type' => 'checkout',
            'status' => 'pending',
            'buyer_name' => 'John Doe',
            'subtotal' => 1000.00,
            'total' => 1000.00,
            'currency' => 'NGN',
        ]);

        Livewire::test(SellerOrders::class)
            ->call('updateStatus', $order->id, 'confirmed')
            ->assertStatus(200);

        expect($order->fresh()->status)->toBe('confirmed');
        expect($order->fresh()->confirmed_at)->not->toBeNull();
    });
});
