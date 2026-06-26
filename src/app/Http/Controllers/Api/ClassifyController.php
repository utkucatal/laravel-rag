<?php

namespace App\Http\Controllers\Api;

use App\Services\Rag\FilterExtractor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClassifyController
{
    public function __invoke(Request $request, FilterExtractor $extractor): JsonResponse
    {
        $text = $request->validate(['text' => ['required', 'string', 'max:2000']])['text'];

        return response()->json(['structured' => $extractor->extract($text)]);
    }
}
