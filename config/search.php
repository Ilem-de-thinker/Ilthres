<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Ranking Weights
    |--------------------------------------------------------------------------
    |
    | Weights used by RankingService to score search results.
    | Total max: 100 points.
    |
    */

    'weights' => [
        'verified' => 30,
        'rating' => 25,
        'activity' => 20,
        'proximity' => 15,
        'affordability' => 5,
        'active' => 5,
    ],

    /*
    |--------------------------------------------------------------------------
    | Proximity Settings
    |--------------------------------------------------------------------------
    */

    'proximity' => [
        'max_distance_km' => 50,
    ],

    /*
    |--------------------------------------------------------------------------
    | Service Keywords
    |--------------------------------------------------------------------------
    |
    | Maps keywords to their category slugs for query classification.
    |
    */

    'service_keywords' => [
        'plumber' => 'plumbing',
        'plumbing' => 'plumbing',
        'pipe' => 'plumbing',
        'leak' => 'plumbing',
        'water' => 'plumbing',
        'electrician' => 'electrical',
        'electrical' => 'electrical',
        'wiring' => 'electrical',
        'power' => 'electrical',
        'light' => 'electrical',
        'hairdresser' => 'beauty',
        'salon' => 'beauty',
        'barber' => 'beauty',
        'hair' => 'beauty',
        'stylist' => 'beauty',
        'nail' => 'beauty',
        'makeup' => 'beauty',
        'spa' => 'beauty',
        'facial' => 'beauty',
        'restaurant' => 'food',
        'food' => 'food',
        'eat' => 'food',
        'dining' => 'food',
        'catering' => 'food',
        'kitchen' => 'food',
        'baking' => 'food',
        'cake' => 'food',
        'chop' => 'food',
        'jeun' => 'food',
        'designer' => 'technology',
        'web' => 'technology',
        'graphic' => 'technology',
        'logo' => 'technology',
        'software' => 'technology',
        'app' => 'technology',
        'digital' => 'technology',
        'cleaning' => 'cleaning',
        'cleaner' => 'cleaning',
        'washer' => 'cleaning',
        'laundry' => 'cleaning',
        'dry cleaning' => 'cleaning',
        'mechanic' => 'automotive',
        'car' => 'automotive',
        'auto' => 'automotive',
        'vehicle' => 'automotive',
        'tyre' => 'automotive',
        'mechanic' => 'automotive',
        'tailor' => 'fashion',
        'fashion' => 'fashion',
        'sewing' => 'fashion',
        'weaving' => 'fashion',
        'knitting' => 'fashion',
        'trainer' => 'fitness',
        'gym' => 'fitness',
        'fitness' => 'fitness',
        'exercise' => 'fitness',
        'yoga' => 'fitness',
        'doctor' => 'health',
        'hospital' => 'health',
        'pharmacy' => 'health',
        'clinic' => 'health',
        'nurse' => 'health',
        'dental' => 'health',
        'lawyer' => 'legal',
        'legal' => 'legal',
        'attorney' => 'legal',
        'consultant' => 'consulting',
        'tutor' => 'education',
        'teacher' => 'education',
        'school' => 'education',
        'lesson' => 'education',
        'training' => 'education',
        'hotel' => 'hospitality',
        'event planner' => 'events',
        'event' => 'events',
        'wedding' => 'events',
        'photographer' => 'photography',
        'photography' => 'photography',
        'video' => 'photography',
        'real estate' => 'real_estate',
        'property' => 'real_estate',
        'house' => 'real_estate',
        'apartment' => 'real_estate',
        'transport' => 'logistics',
        'logistics' => 'logistics',
        'moving' => 'logistics',
        'delivery' => 'logistics',
        'courier' => 'logistics',
        'painter' => 'home_services',
        'architect' => 'construction',
        'construction' => 'construction',
        'building' => 'construction',
        'plumber' => 'plumbing',
        'welder' => 'manufacturing',
        'fabrication' => 'manufacturing',
        'printing' => 'printing',
        'broker' => 'finance',
        'agent' => 'general',
    ],

    /*
    |--------------------------------------------------------------------------
    | Product Keywords
    |--------------------------------------------------------------------------
    */

    'product_keywords' => [
        'phone' => 'electronics',
        'iphone' => 'electronics',
        'android' => 'electronics',
        'samsung' => 'electronics',
        'laptop' => 'electronics',
        'computer' => 'electronics',
        'tablet' => 'electronics',
        'headphone' => 'electronics',
        'earphone' => 'electronics',
        'charger' => 'electronics',
        'cable' => 'electronics',
        'case' => 'electronics',
        'cover' => 'electronics',
        'screen protector' => 'electronics',
        'tv' => 'electronics',
        'television' => 'electronics',
        'monitor' => 'electronics',
        'keyboard' => 'electronics',
        'mouse' => 'electronics',
        'speaker' => 'electronics',
        'camera' => 'electronics',
        'gadget' => 'electronics',
        'power bank' => 'electronics',
        'router' => 'electronics',
        'shirt' => 'fashion',
        'pants' => 'fashion',
        'dress' => 'fashion',
        'shoes' => 'fashion',
        'sneakers' => 'fashion',
        'bag' => 'fashion',
        'watch' => 'fashion',
        'jewelry' => 'fashion',
        'jewellery' => 'fashion',
        'ring' => 'fashion',
        'necklace' => 'fashion',
        'bracelet' => 'fashion',
        'hat' => 'fashion',
        'cap' => 'fashion',
        'gown' => 'fashion',
        'ankara' => 'fashion',
        'lace' => 'fashion',
        'wax' => 'fashion',
        'material' => 'fashion',
        'fabric' => 'fashion',
        'rice' => 'food',
        'beans' => 'food',
        'oil' => 'food',
        'spice' => 'food',
        'organic' => 'food',
        'snack' => 'food',
        'drink' => 'food',
        'water' => 'food',
        'juice' => 'food',
        'wine' => 'food',
        'beer' => 'food',
        'meat' => 'food',
        'fish' => 'food',
        'chicken' => 'food',
        'fruit' => 'food',
        'vegetable' => 'food',
        'furniture' => 'home',
        'chair' => 'home',
        'table' => 'home',
        'bed' => 'home',
        'sofa' => 'home',
        'lamp' => 'home',
        'curtain' => 'home',
        'mattress' => 'home',
        'wardrobe' => 'home',
        'book' => 'education',
        'notebook' => 'education',
        'pen' => 'education',
        'toy' => 'kids',
        'baby' => 'kids',
        'diaper' => 'kids',
        'cosmetic' => 'beauty',
        'perfume' => 'beauty',
        'cream' => 'beauty',
        'soap' => 'beauty',
        'makeup' => 'beauty',
        'lotion' => 'beauty',
        'hair' => 'beauty',
        'wig' => 'beauty',
        'extension' => 'beauty',
        'car' => 'automotive',
        'tyre' => 'automotive',
        'engine' => 'automotive',
        'spare part' => 'automotive',
        'soccer' => 'sports',
        'football' => 'sports',
        'jersey' => 'sports',
        'gym' => 'sports',
        'protein' => 'health',
        'supplement' => 'health',
        'vitamin' => 'health',
        'medicine' => 'health',
    ],

    /*
    |--------------------------------------------------------------------------
    | Synonyms
    |--------------------------------------------------------------------------
    |
    | Expand search queries with synonyms for better recall.
    |
    */

    'synonyms' => [
        'food' => ['meal', 'chop', 'eat', 'hungry', 'restaurant', 'dining'],
        'clothes' => ['clothing', 'wear', 'outfit', 'attire', 'fashion'],
        'phone' => ['mobile', 'smartphone', 'handset', 'cell'],
        'computer' => ['pc', 'desktop', 'laptop', 'notebook'],
        'shoes' => ['footwear', 'sneakers', 'boots', 'slippers', 'sandals'],
        'beauty' => ['salon', 'spa', 'cosmetics', 'skincare', 'glam'],
        'car' => ['vehicle', 'automobile', 'motor', 'ride'],
        'house' => ['home', 'apartment', 'flat', 'property', 'room'],
        'clean' => ['cleaning', 'wash', 'laundry', 'dry clean'],
        'teach' => ['tutor', 'lesson', 'training', 'school', 'class'],
        'fix' => ['repair', 'maintenance', 'service', 'restore'],
        'cheap' => ['affordable', 'budget', 'low cost', 'reasonably priced', 'small money'],
        'expensive' => ['premium', 'luxury', 'high end', 'quality'],
        'good' => ['best', 'top', 'quality', 'excellent', 'fine'],
        'fast' => ['quick', 'rapid', 'urgent', 'speedy'],
        'near' => ['close', 'nearby', 'around', 'around here'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Nigerian Pidgin / Colloquial Mappings
    |--------------------------------------------------------------------------
    |
    | Map local expressions to standard search modifiers.
    |
    */

    'pidgin' => [
        'wahala price' => 'expensive',
        'wahala' => 'expensive',
        'small money' => 'cheap',
        'chop' => 'food',
        'jeun' => 'food',
        'correct thing' => 'quality',
        'correct' => 'quality',
        'level' => 'quality',
        'omenala' => 'traditional',
        'aso ebi' => 'fashion',
        'owambe' => 'events',
        'party' => 'events',
        'pickin' => 'kids',
        'wetin' => '',
        'how far' => '',
        'abeg' => '',
        'no wahala' => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modifier Keywords
    |--------------------------------------------------------------------------
    |
    | Maps keywords to ranking modifier names.
    |
    */

    'modifiers' => [
        'best' => 'rating',
        'top' => 'rating',
        'great' => 'rating',
        'excellent' => 'rating',
        'quality' => 'rating',
        'cheap' => 'price_low',
        'affordable' => 'price_low',
        'budget' => 'price_low',
        'small money' => 'price_low',
        'premium' => 'price_high',
        'luxury' => 'price_high',
        'expensive' => 'price_high',
        'high end' => 'price_high',
        'open now' => 'availability',
        'open' => 'availability',
        'available' => 'availability',
        'verified' => 'is_verified',
        'trusted' => 'is_verified',
        'certified' => 'is_verified',
        'nearby' => 'proximity',
        'near' => 'proximity',
        'close' => 'proximity',
        'fast' => 'speed',
        'quick' => 'speed',
        'urgent' => 'speed',
        'new' => 'recency',
        'newest' => 'recency',
        'latest' => 'recency',
        'popular' => 'popularity',
        'trending' => 'popularity',
        'famous' => 'popularity',
    ],

    /*
    |--------------------------------------------------------------------------
    | Intent Detection
    |--------------------------------------------------------------------------
    |
    | Keywords that indicate user intent for search classification.
    |
    */

    'intents' => [
        'buying' => ['buy', 'purchase', 'order', 'shop', 'get', 'want', 'need'],
        'browsing' => ['browse', 'look', 'see', 'check', 'view', 'explore', 'find'],
        'comparing' => ['compare', 'vs', 'versus', 'better', 'difference', 'which'],
        'booking' => ['book', 'reserve', 'schedule', 'appointment', 'slot'],
        'inquiring' => ['ask', 'question', 'info', 'information', 'details', 'contact'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Price Patterns
    |--------------------------------------------------------------------------
    |
    | Regex patterns for extracting price ranges from queries.
    |
    */

    'price_patterns' => [
        '/under\s+(\d[\d,]*)/i' => ['type' => 'max'],
        '/below\s+(\d[\d,]*)/i' => ['type' => 'max'],
        '/less than\s+(\d[\d,]*)/i' => ['type' => 'max'],
        '/above\s+(\d[\d,]*)/i' => ['type' => 'min'],
        '/over\s+(\d[\d,]*)/i' => ['type' => 'min'],
        '/more than\s+(\d[\d,]*)/i' => ['type' => 'min'],
        '/between\s+(\d[\d,]*)\s+and\s+(\d[\d,]*)/i' => ['type' => 'range'],
        '/from\s+(\d[\d,]*)\s+to\s+(\d[\d,]*)/i' => ['type' => 'range'],
        '/(\d+)k/i' => ['type' => 'max', 'multiplier' => 1000],
        '/(\d+)k\s+to\s+(\d+)k/i' => ['type' => 'range', 'multiplier' => 1000],
    ],

    /*
    |--------------------------------------------------------------------------
    | Location Patterns
    |--------------------------------------------------------------------------
    */

    'location_patterns' => [
        'near me' => 'geolocation',
        'close to me' => 'geolocation',
        'around me' => 'geolocation',
        'in {city}' => 'city',
        'around {area}' => 'city',
        'at {area}' => 'city',
        'inside {area}' => 'city',
    ],

    /*
    |--------------------------------------------------------------------------
    | Search Settings
    |--------------------------------------------------------------------------
    */

    'results_per_page' => 12,
    'max_suggestions' => 8,
    'suggestion_min_chars' => 2,
    'trending_period_days' => 7,
    'trending_limit' => 10,

];
