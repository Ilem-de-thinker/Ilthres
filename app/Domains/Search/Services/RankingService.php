<?php

namespace App\Domains\Search\Services;

use App\Models\Business;
use Illuminate\Database\Eloquent\Model;

class RankingService
{
    protected array $weights;

    protected int $maxDistanceKm;

    public function __construct()
    {
        $this->weights = config('search.weights', [
            'verified' => 30,
            'rating' => 25,
            'activity' => 20,
            'proximity' => 15,
            'affordability' => 5,
            'active' => 5,
        ]);
        $this->maxDistanceKm = config('search.proximity.max_distance_km', 50);
    }

    /**
     * Calculate a relevance score for a business or entity with a business relation.
     */
    public function calculateScore(Model $model, ?array $userLocation): float
    {
        $business = $model instanceof Business ? $model : $model->business;

        if (! $business) {
            return 0.0;
        }

        $score = 0.0;

        $score += $this->verifiedScore($business);
        $score += $this->ratingScore($business);
        $score += $this->activityScore($business);
        $score += $this->proximityScore($business, $userLocation);
        $score += $this->affordabilityScore();
        $score += $this->activeScore($business);

        return $score;
    }

    protected function verifiedScore(Business $business): float
    {
        return $business->is_verified ? $this->weights['verified'] : 0.0;
    }

    protected function ratingScore(Business $business): float
    {
        $rating = $business->average_rating ?? 0;

        return ($rating / 5) * $this->weights['rating'];
    }

    protected function activityScore(Business $business): float
    {
        $recentActivity = $business->products()
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        $recentActivity += $business->services()
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        $activityRatio = min($recentActivity / 5, 1);

        return $activityRatio * $this->weights['activity'];
    }

    protected function proximityScore(Business $business, ?array $userLocation): float
    {
        if (! $userLocation || ! $business->latitude || ! $business->longitude) {
            return 0.0;
        }

        $distance = $this->calculateDistance(
            $userLocation['lat'],
            $userLocation['lng'],
            (float) $business->latitude,
            (float) $business->longitude
        );

        $proximityRatio = max(0, 1 - ($distance / $this->maxDistanceKm));

        return $proximityRatio * $this->weights['proximity'];
    }

    protected function affordabilityScore(): float
    {
        return $this->weights['affordability'] / 2;
    }

    protected function activeScore(Business $business): float
    {
        return $business->is_active ? $this->weights['active'] : 0.0;
    }

    /**
     * Calculate distance between two points using the Haversine formula.
     */
    public function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
