<?php

namespace App\Domains\Promotion\Livewire;

use App\Domains\Promotion\Actions\CreatePromotion;
use App\Domains\Promotion\Actions\UpdatePromotion;
use App\Domains\Promotion\DTOs\PromotionData;
use App\Models\Business;
use App\Models\Category;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\Service;
use Illuminate\Support\Str;
use Livewire\Component;

class PromotionForm extends Component
{
    public ?Business $business = null;
    public ?Promotion $promotion = null;
    public bool $editing = false;

    public string $name = '';
    public ?string $description = null;
    public string $promotionType = 'percentage';
    public string $scope = 'all';
    public string $discountType = 'percentage';
    public ?float $discountValue = null;
    public ?float $fixedPrice = null;
    public ?int $buyX = 2;
    public ?int $buyY = 1;
    public string $buyYDiscountType = 'free';
    public ?float $buyYDiscountValue = null;
    public ?float $minOrderAmount = null;
    public ?int $maxUses = null;
    public ?int $customerLimit = null;
    public int $priority = 0;
    public string $status = 'draft';
    public bool $showCountdown = true;
    public bool $showBadge = true;
    public bool $featured = false;
    public ?string $startsAt = null;
    public ?string $endsAt = null;
    public array $badgeConfig = [
        'color' => 'red',
        'position' => 'top-left',
        'custom_text' => null,
    ];

    public array $selectedProductIds = [];
    public array $selectedServiceIds = [];
    public array $selectedCategoryIds = [];
    public string $productSearch = '';
    public string $serviceSearch = '';

    public function mount(?int $promotionId = null): void
    {
        $user = auth()->user();
        $businesses = $user->businesses;

        if ($businesses->isEmpty()) {
            return;
        }

        $defaultId = session('current_business_id', $businesses->first()->id);
        $this->business = $businesses->find($defaultId) ?? $businesses->first();

        if ($promotionId) {
            $this->promotion = Promotion::where('business_id', $this->business->id)
                ->findOrFail($promotionId);
            $this->editing = true;
            $this->fillFromModel($this->promotion);
        }
    }

    private function fillFromModel(Promotion $promotion): void
    {
        $this->name = $promotion->name;
        $this->description = $promotion->description;
        $this->promotionType = $promotion->promotion_type;
        $this->scope = $promotion->scope;
        $this->discountType = $promotion->discount_type ?? 'percentage';
        $this->discountValue = $promotion->discount_value ? (float) $promotion->discount_value : null;
        $this->fixedPrice = $promotion->fixed_price ? (float) $promotion->fixed_price : null;
        $this->buyX = $promotion->buy_x;
        $this->buyY = $promotion->buy_y;
        $this->buyYDiscountType = $promotion->buy_y_discount_type ?? 'free';
        $this->buyYDiscountValue = $promotion->buy_y_discount_value ? (float) $promotion->buy_y_discount_value : null;
        $this->minOrderAmount = $promotion->min_order_amount ? (float) $promotion->min_order_amount : null;
        $this->maxUses = $promotion->max_uses;
        $this->customerLimit = $promotion->customer_limit;
        $this->priority = $promotion->priority;
        $this->status = $promotion->status;
        $this->showCountdown = $promotion->show_countdown;
        $this->showBadge = $promotion->show_badge;
        $this->featured = $promotion->featured;
        $this->startsAt = $promotion->starts_at?->format('Y-m-d\TH:i');
        $this->endsAt = $promotion->ends_at?->format('Y-m-d\TH:i');
        $this->badgeConfig = array_merge($this->badgeConfig, $promotion->badge_config ?? []);
        $this->selectedProductIds = $promotion->products()->pluck('products.id')->map(fn ($id) => (int) $id)->toArray();
        $this->selectedServiceIds = $promotion->services()->pluck('services.id')->map(fn ($id) => (int) $id)->toArray();
        $this->selectedCategoryIds = $promotion->categories()->pluck('categories.id')->map(fn ($id) => (int) $id)->toArray();
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'promotionType' => ['required', 'in:percentage,fixed_amount,fixed_price,buy_x_get_y,free_shipping,free_item'],
            'scope' => ['required', 'in:all,category,selected_products,selected_services'],
            'discountType' => ['required_if:promotionType,percentage,required_if:promotionType,fixed_amount', 'in:percentage,fixed'],
            'discountValue' => ['required_if:promotionType,percentage,required_if:promotionType,fixed_amount', 'nullable', 'numeric', 'min:0.01'],
            'fixedPrice' => ['required_if:promotionType,fixed_price', 'nullable', 'numeric', 'min:0.01'],
            'buyX' => ['required_if:promotionType,buy_x_get_y', 'nullable', 'integer', 'min:1'],
            'buyY' => ['required_if:promotionType,buy_x_get_y', 'nullable', 'integer', 'min:1'],
            'minOrderAmount' => ['nullable', 'numeric', 'min:0'],
            'maxUses' => ['nullable', 'integer', 'min:1'],
            'customerLimit' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'in:draft,scheduled,active,paused'],
            'startsAt' => ['nullable', 'date'],
            'endsAt' => ['nullable', 'date', 'after_or_equal:startsAt'],
            'selectedCategoryIds' => ['required_if:scope,category', 'array', 'min:1'],
            'selectedProductIds' => ['required_if:scope,selected_products', 'array', 'min:1'],
            'selectedServiceIds' => ['required_if:scope,selected_services', 'array', 'min:1'],
        ];
    }

    protected $messages = [
        'name.required' => 'Enter a promotion name.',
        'discountValue.required_if' => 'Enter a discount value.',
        'fixedPrice.required_if' => 'Enter a fixed price.',
        'selectedCategoryIds.required_if' => 'Select at least one category.',
        'selectedProductIds.required_if' => 'Select at least one product.',
        'selectedServiceIds.required_if' => 'Select at least one service.',
    ];

    public function updatedPromotionType(string $value): void
    {
        $this->reset('discountValue', 'fixedPrice', 'buyX', 'buyY', 'minOrderAmount');
    }

    public function updatedScope(string $value): void
    {
        $this->reset('selectedCategoryIds', 'selectedProductIds', 'selectedServiceIds');
    }

    public function toggleProduct(int $id): void
    {
        if (in_array($id, $this->selectedProductIds)) {
            $this->selectedProductIds = array_values(array_filter($this->selectedProductIds, fn ($pid) => $pid !== $id));
        } else {
            $this->selectedProductIds[] = $id;
        }
    }

    public function toggleService(int $id): void
    {
        if (in_array($id, $this->selectedServiceIds)) {
            $this->selectedServiceIds = array_values(array_filter($this->selectedServiceIds, fn ($sid) => $sid !== $id));
        } else {
            $this->selectedServiceIds[] = $id;
        }
    }

    public function toggleCategory(int $id): void
    {
        if (in_array($id, $this->selectedCategoryIds)) {
            $this->selectedCategoryIds = array_values(array_filter($this->selectedCategoryIds, fn ($cid) => $cid !== $id));
        } else {
            $this->selectedCategoryIds[] = $id;
        }
    }

    public function save(CreatePromotion|UpdatePromotion $action): void
    {
        $this->validate();

        if (! $this->business) {
            return;
        }

        $data = new PromotionData(
            businessId: $this->business->id,
            name: $this->name,
            slug: Str::slug($this->name) . ($this->promotion ? '' : '-' . Str::random(6)),
            description: $this->description ?: null,
            promotionType: $this->promotionType,
            scope: $this->scope,
            discountType: in_array($this->promotionType, ['percentage', 'fixed_amount']) ? $this->discountType : null,
            discountValue: in_array($this->promotionType, ['percentage', 'fixed_amount']) ? $this->discountValue : null,
            fixedPrice: $this->promotionType === 'fixed_price' ? $this->fixedPrice : null,
            buyX: $this->promotionType === 'buy_x_get_y' ? $this->buyX : null,
            buyY: $this->promotionType === 'buy_x_get_y' ? $this->buyY : null,
            buyYDiscountType: $this->promotionType === 'buy_x_get_y' ? $this->buyYDiscountType : null,
            buyYDiscountValue: $this->promotionType === 'buy_x_get_y' ? $this->buyYDiscountValue : null,
            minOrderAmount: $this->promotionType === 'free_shipping' ? $this->minOrderAmount : null,
            maxUses: $this->maxUses,
            customerLimit: $this->customerLimit,
            priority: $this->priority,
            status: $this->status,
            showCountdown: $this->showCountdown,
            showBadge: $this->showBadge,
            featured: $this->featured,
            badgeConfig: $this->badgeConfig,
            startsAt: $this->startsAt,
            endsAt: $this->endsAt,
            createdBy: auth()->id(),
            productIds: $this->scope === 'selected_products' ? $this->selectedProductIds : [],
            serviceIds: $this->scope === 'selected_services' ? $this->selectedServiceIds : [],
            categoryIds: $this->scope === 'category' ? $this->selectedCategoryIds : [],
        );

        if ($this->editing && $this->promotion) {
            $action($this->promotion, $data);
            session()->flash('success', 'Promotion updated successfully.');
        } else {
            app(CreatePromotion::class)($data);
            session()->flash('success', 'Promotion created successfully.');
        }

        $this->redirect(route('dashboard.promotions'), navigate: true);
    }

    public function render()
    {
        if (! $this->business) {
            return view('livewire.promotion.promotion-form', ['hasBusiness' => false]);
        }

        $products = [];
        $services = [];
        $categories = [];

        if ($this->scope === 'selected_products') {
            $products = Product::where('business_id', $this->business->id)
                ->where('is_active', true)
                ->when($this->productSearch, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
                ->orderBy('name')
                ->take(20)
                ->get();
        }

        if ($this->scope === 'selected_services') {
            $services = Service::where('business_id', $this->business->id)
                ->where('is_active', true)
                ->when($this->serviceSearch, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
                ->orderBy('name')
                ->take(20)
                ->get();
        }

        if ($this->scope === 'category') {
            $categories = Category::whereHas('businesses', fn ($q) => $q->where('businesses.id', $this->business->id))
                ->orderBy('name')
                ->get();
        }

        return view('livewire.promotion.promotion-form', [
            'hasBusiness' => true,
            'products' => $products,
            'services' => $services,
            'categories' => $categories,
        ]);
    }
}
