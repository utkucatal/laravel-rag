<?php

namespace App\Http\Controllers\Api;

use App\Services\Rag\AnswerService;
use App\Services\Rag\RetrievalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AskController
{
    public function __invoke(
        Request $request,
        RetrievalService $retrieval,
        AnswerService $answers,
    ): JsonResponse {
        $query = $request->validate(['question' => ['required', 'string', 'max:500']])['question'];

        ['results' => $results] = $retrieval->retrieve($query);

        return response()->json([
            'answer'  => $answers->answer($query, $results),
            'sources' => array_map(fn ($row) => ['id' => $row->product_id, 'url' => $row->url], $results),
        ]);
    }
}
