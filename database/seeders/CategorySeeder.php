<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Professional Services (1-10)
            ['name' => 'Legal Services', 'slug' => 'legal-services', 'icon' => '⚖️', 'sort_order' => 1],
            ['name' => 'Accounting & Tax', 'slug' => 'accounting-tax', 'icon' => '📊', 'sort_order' => 2],
            ['name' => 'Business Consulting', 'slug' => 'business-consulting', 'icon' => '💼', 'sort_order' => 3],
            ['name' => 'Marketing & Advertising', 'slug' => 'marketing-advertising', 'icon' => '📢', 'sort_order' => 4],
            ['name' => 'Digital Marketing', 'slug' => 'digital-marketing', 'icon' => '📱', 'sort_order' => 5],
            ['name' => 'Public Relations', 'slug' => 'public-relations', 'icon' => '📰', 'sort_order' => 6],
            ['name' => 'Human Resources', 'slug' => 'human-resources', 'icon' => '👥', 'sort_order' => 7],
            ['name' => 'Recruitment & Staffing', 'slug' => 'recruitment-staffing', 'icon' => '🔍', 'sort_order' => 8],
            ['name' => 'Insurance Services', 'slug' => 'insurance-services', 'icon' => '🛡️', 'sort_order' => 9],
            ['name' => 'Real Estate', 'slug' => 'real-estate', 'icon' => '🏘️', 'sort_order' => 10],

            // Technology & IT (11-18)
            ['name' => 'IT Services', 'slug' => 'it-services', 'icon' => '💻', 'sort_order' => 11],
            ['name' => 'Software Development', 'slug' => 'software-development', 'icon' => '🖥️', 'sort_order' => 12],
            ['name' => 'Web Design & Development', 'slug' => 'web-design-development', 'icon' => '🌐', 'sort_order' => 13],
            ['name' => 'Mobile App Development', 'slug' => 'mobile-app-development', 'icon' => '📲', 'sort_order' => 14],
            ['name' => 'Cybersecurity', 'slug' => 'cybersecurity', 'icon' => '🔒', 'sort_order' => 15],
            ['name' => 'Cloud Services', 'slug' => 'cloud-services', 'icon' => '☁️', 'sort_order' => 16],
            ['name' => 'Data Analytics', 'slug' => 'data-analytics', 'icon' => '📈', 'sort_order' => 17],
            ['name' => 'AI & Machine Learning', 'slug' => 'ai-machine-learning', 'icon' => '🤖', 'sort_order' => 18],

            // Health & Wellness (19-27)
            ['name' => 'Medical Clinics', 'slug' => 'medical-clinics', 'icon' => '🏥', 'sort_order' => 19],
            ['name' => 'Dental Services', 'slug' => 'dental-services', 'icon' => '🦷', 'sort_order' => 20],
            ['name' => 'Mental Health', 'slug' => 'mental-health', 'icon' => '🧠', 'sort_order' => 21],
            ['name' => 'Physiotherapy', 'slug' => 'physiotherapy', 'icon' => '💪', 'sort_order' => 22],
            ['name' => 'Optometry', 'slug' => 'optometry', 'icon' => '👁️', 'sort_order' => 23],
            ['name' => 'Pharmacy', 'slug' => 'pharmacy', 'icon' => '💊', 'sort_order' => 24],
            ['name' => 'Fitness Centers', 'slug' => 'fitness-centers', 'icon' => '🏋️', 'sort_order' => 25],
            ['name' => 'Yoga & Pilates', 'slug' => 'yoga-pilates', 'icon' => '🧘', 'sort_order' => 26],
            ['name' => 'Nutrition & Dietetics', 'slug' => 'nutrition-dietetics', 'icon' => '🥗', 'sort_order' => 27],

            // Beauty & Personal Care (28-33)
            ['name' => 'Salons & Barbers', 'slug' => 'salons-barbers', 'icon' => '💇', 'sort_order' => 28],
            ['name' => 'Spa & Massage', 'slug' => 'spa-massage', 'icon' => '🧖', 'sort_order' => 29],
            ['name' => 'Nail Salons', 'slug' => 'nail-salons', 'icon' => '💅', 'sort_order' => 30],
            ['name' => 'Makeup & Beauty', 'slug' => 'makeup-beauty', 'icon' => '💄', 'sort_order' => 31],
            ['name' => 'Skincare Clinics', 'slug' => 'skincare-clinics', 'icon' => '🧴', 'sort_order' => 32],
            ['name' => 'Tattoo & Piercing', 'slug' => 'tattoo-piercing', 'icon' => '🎨', 'sort_order' => 33],

            // Home & Living (34-42)
            ['name' => 'Home Renovation', 'slug' => 'home-renovation', 'icon' => '🔨', 'sort_order' => 34],
            ['name' => 'Interior Design', 'slug' => 'interior-design', 'icon' => '🛋️', 'sort_order' => 35],
            ['name' => 'Landscaping & Gardening', 'slug' => 'landscaping-gardening', 'icon' => '🌿', 'sort_order' => 36],
            ['name' => 'Cleaning Services', 'slug' => 'cleaning-services', 'icon' => '🧹', 'sort_order' => 37],
            ['name' => 'Plumbing', 'slug' => 'plumbing', 'icon' => '🔧', 'sort_order' => 38],
            ['name' => 'Electrical Services', 'slug' => 'electrical-services', 'icon' => '⚡', 'sort_order' => 39],
            ['name' => 'HVAC Services', 'slug' => 'hvac-services', 'icon' => '🌡️', 'sort_order' => 40],
            ['name' => 'Pest Control', 'slug' => 'pest-control', 'icon' => '🐜', 'sort_order' => 41],
            ['name' => 'Moving & Storage', 'slug' => 'moving-storage', 'icon' => '📦', 'sort_order' => 42],

            // Food & Hospitality (43-48)
            ['name' => 'Restaurants', 'slug' => 'restaurants', 'icon' => '🍽️', 'sort_order' => 43],
            ['name' => 'Cafes & Coffee Shops', 'slug' => 'cafes-coffee-shops', 'icon' => '☕', 'sort_order' => 44],
            ['name' => 'Bakeries', 'slug' => 'bakeries', 'icon' => '🍞', 'sort_order' => 45],
            ['name' => 'Catering Services', 'slug' => 'catering-services', 'icon' => '🧑‍🍳', 'sort_order' => 46],
            ['name' => 'Food Trucks', 'slug' => 'food-trucks', 'icon' => '🚚', 'sort_order' => 47],
            ['name' => 'Hotels & Accommodation', 'slug' => 'hotels-accommodation', 'icon' => '🏨', 'sort_order' => 48],

            // Education & Training (49-53)
            ['name' => 'Schools & Academies', 'slug' => 'schools-academies', 'icon' => '🏫', 'sort_order' => 49],
            ['name' => 'Tutoring Services', 'slug' => 'tutoring-services', 'icon' => '📚', 'sort_order' => 50],
            ['name' => 'Language Classes', 'slug' => 'language-classes', 'icon' => '🗣️', 'sort_order' => 51],
            ['name' => 'Vocational Training', 'slug' => 'vocational-training', 'icon' => '🛠️', 'sort_order' => 52],
            ['name' => 'Music & Art Lessons', 'slug' => 'music-art-lessons', 'icon' => '🎵', 'sort_order' => 53],

            // Events & Entertainment (54-58)
            ['name' => 'Event Planning', 'slug' => 'event-planning', 'icon' => '🎉', 'sort_order' => 54],
            ['name' => 'Photography', 'slug' => 'photography', 'icon' => '📸', 'sort_order' => 55],
            ['name' => 'Videography', 'slug' => 'videography', 'icon' => '🎥', 'sort_order' => 56],
            ['name' => 'DJ & Entertainment', 'slug' => 'dj-entertainment', 'icon' => '🎧', 'sort_order' => 57],
            ['name' => 'Catering & Events', 'slug' => 'catering-events', 'icon' => '🍾', 'sort_order' => 58],

            // Automotive & Transportation (59-60)
            ['name' => 'Auto Repair & Mechanics', 'slug' => 'auto-repair-mechanics', 'icon' => '🔧', 'sort_order' => 59],
            ['name' => 'Car Rentals', 'slug' => 'car-rentals', 'icon' => '🚗', 'sort_order' => 60],

            // 🌿 Pet Services (61-64)
            ['name' => 'Pet Grooming', 'slug' => 'pet-grooming', 'icon' => '🐕', 'sort_order' => 61],
            ['name' => 'Veterinary Services', 'slug' => 'veterinary-services', 'icon' => '🏥', 'sort_order' => 62],
            ['name' => 'Pet Boarding & Sitting', 'slug' => 'pet-boarding-sitting', 'icon' => '🐈', 'sort_order' => 63],
            ['name' => 'Pet Training', 'slug' => 'pet-training', 'icon' => '🐾', 'sort_order' => 64],

            // 👶 Childcare & Family (65-68)
            ['name' => 'Daycare Centers', 'slug' => 'daycare-centers', 'icon' => '🧸', 'sort_order' => 65],
            ['name' => 'Nanny Services', 'slug' => 'nanny-services', 'icon' => '👶', 'sort_order' => 66],
            ['name' => 'Child Enrichment Programs', 'slug' => 'child-enrichment-programs', 'icon' => '🎨', 'sort_order' => 67],
            ['name' => 'Elderly Care Services', 'slug' => 'elderly-care-services', 'icon' => '👴', 'sort_order' => 68],

            // 🛒 Retail & Shopping (69-73)
            ['name' => 'Boutique Stores', 'slug' => 'boutique-stores', 'icon' => '👗', 'sort_order' => 69],
            ['name' => 'Furniture Stores', 'slug' => 'furniture-stores', 'icon' => '🛋️', 'sort_order' => 70],
            ['name' => 'Electronics Stores', 'slug' => 'electronics-stores', 'icon' => '📱', 'sort_order' => 71],
            ['name' => 'Bookstores', 'slug' => 'bookstores', 'icon' => '📚', 'sort_order' => 72],
            ['name' => 'Specialty Food Shops', 'slug' => 'specialty-food-shops', 'icon' => '🛒', 'sort_order' => 73],

            // 🏢 Commercial & Industrial (74-77)
            ['name' => 'Office Supply Stores', 'slug' => 'office-supply-stores', 'icon' => '📎', 'sort_order' => 74],
            ['name' => 'Printing & Copying', 'slug' => 'printing-copying', 'icon' => '🖨️', 'sort_order' => 75],
            ['name' => 'Warehousing & Logistics', 'slug' => 'warehousing-logistics', 'icon' => '📦', 'sort_order' => 76],
            ['name' => 'Industrial Equipment', 'slug' => 'industrial-equipment', 'icon' => '🏭', 'sort_order' => 77],

            // 🌱 Eco & Sustainability (78-80)
            ['name' => 'Solar Energy Installation', 'slug' => 'solar-energy-installation', 'icon' => '☀️', 'sort_order' => 78],
            ['name' => 'Recycling Services', 'slug' => 'recycling-services', 'icon' => '♻️', 'sort_order' => 79],
            ['name' => 'Sustainable Products', 'slug' => 'sustainable-products', 'icon' => '🌍', 'sort_order' => 80],

            // 🎯 Hobbies & Recreation (81-84)
            ['name' => 'Sports Equipment Stores', 'slug' => 'sports-equipment-stores', 'icon' => '⚽', 'sort_order' => 81],
            ['name' => 'Hobby Shops', 'slug' => 'hobby-shops', 'icon' => '🎯', 'sort_order' => 82],
            ['name' => 'Outdoor & Camping', 'slug' => 'outdoor-camping', 'icon' => '⛺', 'sort_order' => 83],
            ['name' => 'Gaming Centers', 'slug' => 'gaming-centers', 'icon' => '🎮', 'sort_order' => 84],

            // 🚀 Specialized Services (85-88)
            ['name' => 'Notary Services', 'slug' => 'notary-services', 'icon' => '📜', 'sort_order' => 85],
            ['name' => 'Translation Services', 'slug' => 'translation-services', 'icon' => '🌐', 'sort_order' => 86],
            ['name' => 'Private Investigators', 'slug' => 'private-investigators', 'icon' => '🔍', 'sort_order' => 87],
            ['name' => 'Security Services', 'slug' => 'security-services', 'icon' => '🚨', 'sort_order' => 88],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
