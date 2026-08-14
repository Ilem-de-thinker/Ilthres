<?php

namespace App\Domains\Search\Http;

use App\Domains\Search\Services\SearchParser;
use App\Domains\Search\Services\SuggestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController
{
    public function suggestions(Request $request, SuggestService $suggestService): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:2|max:200',
        ]);

        $userLocation = session('user_location');
        $suggestions = $suggestService->suggest($request->input('q'), $userLocation);

        return response()->json(['suggestions' => $suggestions]);
    }

    public function parse(Request $request, SearchParser $parser): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:1|max:500',
        ]);

        $parsed = $parser->parse($request->input('q'));

        return response()->json(['parsed' => $parsed]);
    }
}
