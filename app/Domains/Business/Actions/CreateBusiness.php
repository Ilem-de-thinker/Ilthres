<?php

namespace App\Domains\Business\Actions;

use App\Domains\Business\DTOs\BusinessData;
use App\Domains\Business\Events\BusinessCreated;
use App\Models\Business;
use Illuminate\Support\Str;

class CreateBusiness
{
    public function __invoke(BusinessData $data): Business
    {
        $slug = $data->slug ?: Str::slug($data->name);
        $slug = $this->ensureUniqueSlug($slug);

        $business = Business::create([
            'user_id' => $data->userId,
            'category_id' => $data->categoryId,
            'name' => $data->name,
            'slug' => $slug,
            'type' => $data->type,
            'description' => $data->description,
            'about' => $data->about,
            'email' => $data->email,
            'phone' => $data->phone,
            'whatsapp' => $data->whatsapp,
            'address' => $data->address,
            'city' => $data->city,
            'state' => $data->state,
            'country' => $data->country ?? 'Nigeria',
            'latitude' => $data->latitude,
            'longitude' => $data->longitude,
            'logo' => $data->logo,
            'cover' => $data->cover,
            'is_active' => true,
            'is_discoverable' => $data->isDiscoverable,
            'is_verified' => false,
        ]);

        event(new BusinessCreated($business));

        return $business;
    }

    private function ensureUniqueSlug(string $slug): string
    {
        $original = $slug;
        $suffix = 1;

        while (Business::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $suffix++;
        }

        return $slug;
    }
}
