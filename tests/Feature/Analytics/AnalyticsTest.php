<?php

use App\Domains\Analytics\Actions\GenerateReport;
use App\Domains\Analytics\Actions\RecordEvent;
use App\Domains\Analytics\Livewire\AnalyticsDashboard;
use App\Domains\Analytics\Livewire\TopItems;
use App\Domains\Analytics\Livewire\VisitorsChart;
use App\Models\AnalyticsEvent;
use App\Models\Business;
use App\Models\Product;
use App\Models\User;
use Livewire\Livewire;

uses()->group('analytics');

beforeEach(function () {
    $this->user = User::factory()->create(['onboarding_completed' => true]);
    $this->business = Business::factory()->create(['user_id' => $this->user->id]);
    session(['current_business_id' => $this->business->id]);
    $this->actingAs($this->user);
});

describe('Record Event', function () {
    it('records a page view event', function () {
        app(RecordEvent::class)(
            business: $this->business,
            type: AnalyticsEvent::TYPE_PAGE_VIEW,
            source: 'profile',
        );

        $this->assertDatabaseHas('analytics_events', [
            'business_id' => $this->business->id,
            'type' => AnalyticsEvent::TYPE_PAGE_VIEW,
            'source' => 'profile',
        ]);
    });

    it('records a whatsapp click event', function () {
        app(RecordEvent::class)(
            business: $this->business,
            type: AnalyticsEvent::TYPE_WHATSAPP_CLICK,
            source: 'profile',
        );

        $this->assertDatabaseHas('analytics_events', [
            'business_id' => $this->business->id,
            'type' => AnalyticsEvent::TYPE_WHATSAPP_CLICK,
        ]);
    });

    it('records a phone click event', function () {
        app(RecordEvent::class)(
            business: $this->business,
            type: AnalyticsEvent::TYPE_PHONE_CLICK,
            source: 'profile',
        );

        $this->assertDatabaseHas('analytics_events', [
            'business_id' => $this->business->id,
            'type' => AnalyticsEvent::TYPE_PHONE_CLICK,
        ]);
    });

    it('records event with metadata', function () {
        app(RecordEvent::class)(
            business: $this->business,
            type: AnalyticsEvent::TYPE_PRODUCT_VIEW,
            source: 'profile',
            metadata: ['item_id' => 1, 'item_name' => 'Test Product'],
        );

        $event = AnalyticsEvent::where('business_id', $this->business->id)
            ->where('type', AnalyticsEvent::TYPE_PRODUCT_VIEW)
            ->first();

        expect($event->metadata)->toBe(['item_id' => 1, 'item_name' => 'Test Product']);
    });
});

describe('Generate Report', function () {
    beforeEach(function () {
        AnalyticsEvent::factory()->pageView()->count(10)->create(['business_id' => $this->business->id]);
        AnalyticsEvent::factory()->whatsappClick()->count(3)->create(['business_id' => $this->business->id]);
        AnalyticsEvent::factory()->productView()->count(2)->create(['business_id' => $this->business->id]);
    });

    it('returns total view count', function () {
        $report = app(GenerateReport::class);
        $totalViews = $report->totalViews($this->business);

        expect($totalViews)->toBe(10);
    });

    it('returns views grouped by day', function () {
        $report = app(GenerateReport::class);
        $viewsByDay = $report->viewsByDay($this->business, 14);

        expect($viewsByDay)->toBeArray()
            ->and($viewsByDay)->not->toBeEmpty();
    });

    it('returns top items', function () {
        $report = app(GenerateReport::class);
        $topItems = $report->topItems($this->business, AnalyticsEvent::TYPE_PRODUCT_VIEW);

        expect($topItems)->toBeArray();
    });

    it('returns today vs yesterday comparison', function () {
        $report = app(GenerateReport::class);
        $todayVsYesterday = $report->todayVsYesterday($this->business, AnalyticsEvent::TYPE_PAGE_VIEW);

        expect($todayVsYesterday)->toHaveKeys(['today', 'yesterday']);
    });
});

describe('Analytics Dashboard', function () {
    it('renders analytics page', function () {
        $this->get('/dashboard/analytics')->assertSuccessful();
    });

    it('shows visitor counts', function () {
        AnalyticsEvent::factory()->count(5)->pageView()->create(['business_id' => $this->business->id]);

        Livewire::test(AnalyticsDashboard::class)
            ->assertSuccessful();
    });
});

describe('Visitors Chart', function () {
    it('renders chart data', function () {
        AnalyticsEvent::factory()->count(3)->pageView()->create(['business_id' => $this->business->id]);

        Livewire::test(VisitorsChart::class, ['business' => $this->business])
            ->assertSuccessful();
    });
});

describe('Top Items', function () {
    it('renders top items', function () {
        AnalyticsEvent::factory()->productView()->count(2)->create(['business_id' => $this->business->id]);

        Livewire::test(TopItems::class, ['business' => $this->business])
            ->assertSuccessful();
    });

    it('shows product views', function () {
        $product = Product::factory()->create([
            'business_id' => $this->business->id,
            'is_active' => true,
            'name' => 'Popular Product',
        ]);

        AnalyticsEvent::factory()->count(3)->productView()->create([
            'business_id' => $this->business->id,
            'metadata' => ['product_id' => $product->id],
        ]);

        Livewire::test(TopItems::class, ['business' => $this->business])
            ->assertSee('Popular Product');
    });
});

describe('Click Tracking Route', function () {
    it('tracks whatsapp click via route', function () {
        $this->get("/track/{$this->business->id}/whatsapp_click?source=profile")
            ->assertSuccessful()
            ->assertNoContent();

        $this->assertDatabaseHas('analytics_events', [
            'business_id' => $this->business->id,
            'type' => AnalyticsEvent::TYPE_WHATSAPP_CLICK,
            'source' => 'profile',
        ]);
    });

    it('tracks phone click via route', function () {
        $this->get("/track/{$this->business->id}/phone_click?source=profile")
            ->assertSuccessful()
            ->assertNoContent();

        $this->assertDatabaseHas('analytics_events', [
            'business_id' => $this->business->id,
            'type' => AnalyticsEvent::TYPE_PHONE_CLICK,
            'source' => 'profile',
        ]);
    });

    it('rejects invalid click type', function () {
        $this->get("/track/{$this->business->id}/invalid_type")->assertNotFound();
    });
});
