<?php

namespace App\Domains\Search\Services;

class SearchParser
{
    protected array $serviceKeywords;

    protected array $productKeywords;

    protected array $modifiers;

    protected array $synonyms;

    protected array $pidgin;

    protected array $intents;

    protected array $pricePatterns;

    protected array $locationPatterns;

    public function __construct()
    {
        $this->serviceKeywords = config('search.service_keywords', []);
        $this->productKeywords = config('search.product_keywords', []);
        $this->modifiers = config('search.modifiers', []);
        $this->synonyms = config('search.synonyms', []);
        $this->pidgin = config('search.pidgin', []);
        $this->intents = config('search.intents', []);
        $this->pricePatterns = config('search.price_patterns', []);
        $this->locationPatterns = config('search.location_patterns', []);
    }

    public function parse(string $query): array
    {
        $result = [
            'query_type' => null,
            'service_type' => null,
            'product_type' => null,
            'service_category' => null,
            'product_category' => null,
            'location' => null,
            'modifiers' => [],
            'intent' => null,
            'price_range' => null,
            'synonyms' => [],
            'clean_query' => $query,
            'raw_query' => $query,
        ];

        $this->expandPidgin($result, $query);
        $this->extractLocation($result, $query);
        $this->extractModifiers($result, $query);
        $this->extractPriceRange($result, $query);
        $this->detectIntent($result, $query);

        $cleanQuery = $this->cleanQuery($query, $result);
        $result['clean_query'] = trim($cleanQuery);

        $this->expandSynonyms($result, $result['clean_query']);
        $this->classifyQuery($result, $result['clean_query']);

        return $result;
    }

    protected function expandPidgin(array &$result, string $query): void
    {
        $lowerQuery = strtolower($query);

        foreach ($this->pidgin as $pidginPhrase => $standard) {
            if ($standard === '' || ! str_contains($lowerQuery, $pidginPhrase)) {
                continue;
            }

            $result['raw_query'] = str_ireplace($pidginPhrase, $standard, $result['raw_query']);
        }
    }

    protected function extractLocation(array &$result, string $query): void
    {
        if (preg_match('/near\s+me|close\s+to\s+me|around\s+me/i', $query)) {
            $result['location'] = ['type' => 'geolocation', 'value' => null];

            return;
        }

        $cityPatterns = [
            '/\bin\s+([A-Za-z\s]+?)(?:\s+(?:now|open|best|cheap|near|available|for|that|who)|$)/i',
            '/\baround\s+([A-Za-z\s]+?)(?:\s+(?:now|open|best|cheap|near|available|for|that|who)|$)/i',
            '/\bat\s+([A-Za-z\s]+?)(?:\s+(?:now|open|best|cheap|near|available|for|that|who)|$)/i',
            '/\binside\s+([A-Za-z\s]+?)(?:\s+(?:now|open|best|cheap|near|available|for|that|who)|$)/i',
        ];

        foreach ($cityPatterns as $pattern) {
            if (preg_match($pattern, $query, $matches)) {
                $value = trim($matches[1]);

                if (strlen($value) > 1) {
                    $result['location'] = ['type' => 'city', 'value' => $value];

                    return;
                }
            }
        }
    }

    protected function extractModifiers(array &$result, string $query): void
    {
        $lowerQuery = strtolower($query);

        foreach ($this->modifiers as $keyword => $modifier) {
            if (str_contains($lowerQuery, $keyword)) {
                $result['modifiers'][] = $modifier;
            }
        }

        $result['modifiers'] = array_unique($result['modifiers']);
    }

    protected function extractPriceRange(array &$result, string $query): void
    {
        foreach ($this->pricePatterns as $pattern => $config) {
            if (preg_match($pattern, $query, $matches)) {
                $multiplier = $config['multiplier'] ?? 1;

                match ($config['type']) {
                    'max' => $result['price_range'] = [
                        'min' => null,
                        'max' => (int) str_replace(',', '', $matches[1]) * $multiplier,
                    ],
                    'min' => $result['price_range'] = [
                        'min' => (int) str_replace(',', '', $matches[1]) * $multiplier,
                        'max' => null,
                    ],
                    'range' => $result['price_range'] = [
                        'min' => (int) str_replace(',', '', $matches[1]) * $multiplier,
                        'max' => (int) str_replace(',', '', $matches[2]) * $multiplier,
                    ],
                };

                return;
            }
        }
    }

    protected function detectIntent(array &$result, string $query): void
    {
        $lowerQuery = strtolower($query);

        foreach ($this->intents as $intent => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($lowerQuery, $keyword)) {
                    $result['intent'] = $intent;

                    return;
                }
            }
        }
    }

    protected function cleanQuery(string $query, array $result): string
    {
        $clean = $query;

        $clean = preg_replace('/near\s+me|close\s+to\s+me|around\s+me/i', '', $clean);
        $clean = preg_replace('/in\s+[A-Za-z\s]+(?=\s|$)/i', '', $clean);
        $clean = preg_replace('/around\s+[A-Za-z\s]+(?=\s|$)/i', '', $clean);
        $clean = preg_replace('/at\s+[A-Za-z\s]+(?=\s|$)/i', '', $clean);

        $cleanerWords = array_merge(
            array_keys($this->modifiers),
            ['i need', 'i want', 'find me', 'looking for', 'where can i', 'show me', 'help me find']
        );

        foreach ($cleanerWords as $word) {
            $clean = preg_replace('/\b'.preg_quote($word, '/').'\b/i', '', $clean);
        }

        if ($result['price_range']) {
            $clean = preg_replace('/under\s+\d[\d,]*/i', '', $clean);
            $clean = preg_replace('/below\s+\d[\d,]*/i', '', $clean);
            $clean = preg_replace('/less than\s+\d[\d,]*/i', '', $clean);
            $clean = preg_replace('/above\s+\d[\d,]*/i', '', $clean);
            $clean = preg_replace('/over\s+\d[\d,]*/i', '', $clean);
            $clean = preg_replace('/more than\s+\d[\d,]*/i', '', $clean);
            $clean = preg_replace('/between\s+\d[\d,]*\s+and\s+\d[\d,]*/i', '', $clean);
            $clean = preg_replace('/from\s+\d[\d,]*\s+to\s+\d[\d,]*/i', '', $clean);
            $clean = preg_replace('/\d+k/i', '', $clean);
        }

        $clean = preg_replace('/\s+/', ' ', $clean);

        return trim($clean);
    }

    protected function expandSynonyms(array &$result, string $cleanQuery): void
    {
        $lowerQuery = strtolower($cleanQuery);
        $expandedTerms = [];

        foreach ($this->synonyms as $base => $synonymList) {
            if (str_contains($lowerQuery, $base)) {
                $result['synonyms'][] = $base;
                $expandedTerms = array_merge($expandedTerms, $synonymList);
            }

            foreach ($synonymList as $synonym) {
                if (str_contains($lowerQuery, $synonym)) {
                    $result['synonyms'][] = $synonym;
                    $expandedTerms[] = $base;
                    $expandedTerms = array_merge($expandedTerms, array_diff($synonymList, [$synonym]));
                }
            }
        }

        $result['expanded_terms'] = array_unique($expandedTerms);
    }

    protected function classifyQuery(array &$result, string $cleanQuery): void
    {
        $lowerQuery = strtolower($cleanQuery);
        $isService = false;
        $isProduct = false;
        $matchedServiceCategory = null;
        $matchedProductCategory = null;

        foreach ($this->serviceKeywords as $keyword => $category) {
            if (str_contains($lowerQuery, $keyword)) {
                $isService = true;
                $matchedServiceCategory = $category;

                break;
            }
        }

        foreach ($this->productKeywords as $keyword => $category) {
            if (str_contains($lowerQuery, $keyword)) {
                $isProduct = true;
                $matchedProductCategory = $category;

                break;
            }
        }

        if ($isService && $isProduct) {
            $result['query_type'] = 'both';
            $result['service_type'] = $cleanQuery;
            $result['product_type'] = $cleanQuery;
        } elseif ($isService) {
            $result['query_type'] = 'service';
            $result['service_type'] = $cleanQuery;
        } elseif ($isProduct) {
            $result['query_type'] = 'product';
            $result['product_type'] = $cleanQuery;
        } else {
            $result['query_type'] = 'general';
            $result['service_type'] = $cleanQuery;
            $result['product_type'] = $cleanQuery;
        }

        $result['service_category'] = $matchedServiceCategory;
        $result['product_category'] = $matchedProductCategory;
    }
}
