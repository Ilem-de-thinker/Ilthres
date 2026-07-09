<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Fashion & Apparel', 'slug' => 'fashion-apparel', 'icon' => '👗', 'sort_order' => 1],
            ['name' => 'Food & Groceries', 'slug' => 'food-groceries', 'icon' => '🍽️', 'sort_order' => 2],
            ['name' => 'Electronics', 'slug' => 'electronics', 'icon' => '💻', 'sort_order' => 3],
            ['name' => 'Health & Beauty', 'slug' => 'health-beauty', 'icon' => '💄', 'sort_order' => 4],
            ['name' => 'Home & Furniture', 'slug' => 'home-furniture', 'icon' => '🏠', 'sort_order' => 5],
            ['name' => 'Salon & Spa', 'slug' => 'salon-spa', 'icon' => '💇', 'sort_order' => 6],
            ['name' => 'Medical & Dental', 'slug' => 'medical-dental', 'icon' => '🏥', 'sort_order' => 7],
            ['name' => 'Auto & Mechanics', 'slug' => 'auto-mechanics', 'icon' => '🔧', 'sort_order' => 8],
            ['name' => 'Legal & Consulting', 'slug' => 'legal-consulting', 'icon' => '⚖️', 'sort_order' => 9],
            ['name' => 'Fitness & Gym', 'slug' => 'fitness-gym', 'icon' => '💪', 'sort_order' => 10],
            ['name' => 'Photography & Events', 'slug' => 'photography-events', 'icon' => '📸', 'sort_order' => 11],
            ['name' => 'Education & Tutoring', 'slug' => 'education-tutoring', 'icon' => '📚', 'sort_order' => 12],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
