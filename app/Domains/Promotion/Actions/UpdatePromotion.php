<?php

namespace App\Domains\Promotion\Actions;

use App\Domains\Promotion\DTOs\PromotionData;
use App\Models\Promotion;

class UpdatePromotion
{
    public function __invoke(Promotion $promotion, PromotionData $data): Promotion
    {
        $promotion->update([
            'name' => $data->name,
            'slug' => $data->slug,
            'description' => $data->description,
            'promotion_type' => $data->promotionType,
            'scope' => $data->scope,
            'discount_type' => $data->discountType,
            'discount_value' => $data->discountValue,
            'fixed_price' => $data->fixedPrice,
            'buy_x' => $data->buyX,
            'buy_y' => $data->buyY,
            'buy_y_discount_type' => $data->buyYDiscountType,
            'buy_y_discount_value' => $data->buyYDiscountValue,
            'min_order_amount' => $data->minOrderAmount,
            'max_uses' => $data->maxUses,
            'customer_limit' => $data->customerLimit,
            'priority' => $data->priority,
            'status' => $data->status,
            'banner_image' => $data->bannerImage,
            'show_countdown' => $data->showCountdown,
            'show_badge' => $data->showBadge,
            'featured' => $data->featured,
            'badge_config' => $data->badgeConfig,
            'starts_at' => $data->startsAt,
            'ends_at' => $data->endsAt,
        ]);

        $promotion->products()->sync($data->productIds ?? []);
        $promotion->services()->sync($data->serviceIds ?? []);
        $promotion->categories()->sync($data->categoryIds ?? []);

        return $promotion;
    }
}
