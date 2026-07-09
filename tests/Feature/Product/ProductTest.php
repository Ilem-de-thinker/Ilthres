<?php

use App\Domains\Product\Actions\CreateProduct;
use App\Domains\Product\Actions\DeleteProduct;
use App\Domains\Product\Actions\ToggleStatus;
use App\Domains\Product\Actions\UpdateProduct;
use App\Domains\Product\DTOs\ProductData;
use App\Domains\Product\Livewire\ProductCreate;
use App\Domains\Product\Livewire\ProductEdit;
use App\Domains\Product\Livewire\ProductList;
use App\Domains\Product\Livewire\ProductVariants;
use App\Models\Business;
use App\Models\Product;
use App\Models\User;
use Livewire\Livewire;

uses()->group('product');

beforeEach(function () {
    $this->user = User::factory()->create(['onboarding_completed' => true]);
    $this->business = Business::factory()->productStore()->create(['user_id' => $this->user->id]);
    session(['current_business_id' => $this->business->id]);
    $this->actingAs($this->user);
});

describe('Product List', function () {
    it('renders product list page', function () {
        $this->get('/dashboard/products')->assertSuccessful();
    });

    it('shows empty state', function () {
        Livewire::test(ProductList::class)
            ->assertSee('No products yet');
    });

    it('lists products', function () {
        Product::factory()->count(3)->create(['business_id' => $this->business->id]);

        Livewire::test(ProductList::class)
            ->assertSuccessful();
    });
});

describe('Product Create', function () {
    it('renders create product page', function () {
        $this->get('/dashboard/products/create')->assertSuccessful();
    });

    it('creates a product', function () {
        Livewire::test(ProductCreate::class)
            ->set('name', 'New Product')
            ->set('price', 2500)
            ->set('description', 'A great product')
            ->call('save')
            ->assertRedirect();

        $this->assertDatabaseHas('products', [
            'business_id' => $this->business->id,
            'name' => 'New Product',
            'price' => 2500.00,
        ]);
    });

    it('validates required fields', function () {
        Livewire::test(ProductCreate::class)
            ->call('save')
            ->assertHasErrors(['name', 'price']);
    });
});

describe('Product Edit', function () {
    it('renders edit product page', function () {
        $product = Product::factory()->create(['business_id' => $this->business->id]);

        $this->get("/dashboard/products/{$product->id}/edit")->assertSuccessful();
    });

    it('updates product details', function () {
        $product = Product::factory()->create(['business_id' => $this->business->id, 'name' => 'Old Name']);

        Livewire::test(ProductEdit::class, ['productId' => $product->id])
            ->set('name', 'Updated Name')
            ->set('price', 5000)
            ->call('save')
            ->assertRedirect();

        expect($product->fresh()->name)->toBe('Updated Name');
    });
});

describe('Product Variants', function () {
    it('renders variants page', function () {
        $product = Product::factory()->create(['business_id' => $this->business->id]);

        $this->get("/dashboard/products/{$product->id}/variants")->assertSuccessful();
    });

    it('saves variants', function () {
        $product = Product::factory()->create(['business_id' => $this->business->id]);
        $variantsData = [
            ['name' => 'Small', 'price' => 1000],
            ['name' => 'Large', 'price' => 2000],
        ];

        Livewire::test(ProductVariants::class, ['productId' => $product->id])
            ->set('variants', $variantsData)
            ->call('save')
            ->assertSuccessful();
    });
});

describe('Product Actions', function () {
    it('toggles product status', function () {
        $product = Product::factory()->create([
            'business_id' => $this->business->id,
            'is_active' => true,
        ]);

        app(ToggleStatus::class)($product);

        expect($product->fresh()->is_active)->toBeFalse();
    });

    it('soft deletes a product', function () {
        $product = Product::factory()->create(['business_id' => $this->business->id]);

        app(DeleteProduct::class)($product);

        expect(Product::find($product->id))->toBeNull()
            ->and(Product::withTrashed()->find($product->id))->not->toBeNull();
    });

    it('creates product via action', function () {
        $data = new ProductData(
            businessId: $this->business->id,
            name: 'Action Product',
            slug: 'action-product',
            description: 'Created via action',
            price: 1500.00,
            comparePrice: null,
            currency: 'NGN',
            images: null,
            variants: null,
            isActive: true,
            isFeatured: false,
            isPublic: true,
            sortOrder: 0,
            categoryId: null,
        );

        $product = app(CreateProduct::class)($data);

        expect($product)->toBeInstanceOf(Product::class)
            ->and($product->name)->toBe('Action Product');
    });

    it('updates product via action', function () {
        $product = Product::factory()->create(['business_id' => $this->business->id]);

        $data = new ProductData(
            businessId: $this->business->id,
            name: 'Updated Action',
            slug: 'updated-action',
            description: 'Updated',
            price: 3000.00,
            comparePrice: null,
            currency: 'NGN',
            images: null,
            variants: null,
            isActive: true,
            isFeatured: false,
            isPublic: true,
            sortOrder: 0,
            categoryId: null,
        );

        $updated = app(UpdateProduct::class)($product, $data);

        expect($updated->name)->toBe('Updated Action');
    });
});

describe('Product Authorization', function () {
    it('denies access to another business product', function () {
        $otherBusiness = Business::factory()->productStore()->create();
        $product = Product::factory()->create(['business_id' => $otherBusiness->id]);

        $this->get("/dashboard/products/{$product->id}/edit")->assertForbidden();
    });
});
