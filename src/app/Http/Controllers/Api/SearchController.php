<?php

namespace App\Http\Controllers\Api;

use App\Services\Rag\RetrievalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController
{
    public function __invoke(Request $request, RetrievalService $retrieval): JsonResponse
    {
        $query = $request->validate(['q' => ['required', 'string', 'max:255']])['q'];

        ['filters' => $filters, 'results' => $results] = $retrieval->retrieve($query);

        return response()->json([
            'filters' => $filters,
            'results' => array_map(fn ($row) => [
                'id'    => $row->product_id,
                'title' => $row->title,
                'url'   => $row->url,
                'score' => round($row->sim, 3),
            ], $results),
        ]);
    }
}
