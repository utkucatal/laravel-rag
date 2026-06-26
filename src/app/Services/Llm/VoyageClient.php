<?php

namespace App\Services\Llm;

use Illuminate\Support\Facades\Http;
class VoyageClient
{
    public function embed(array $texts, string $inputType = 'document'): array
    {
        $response = Http::withToken(config('services.voyage.key'))
            ->asJson()
            ->post('https://api.voyageai.com/v1/embeddings', [
                'input'      => $texts,
                'model'      => config('rag.embed_model'),
                'input_type' => $inputType,
            ])
            ->throw()
            ->json();

        return array_map(fn ($row) => $row['embedding'], $response['data']);
    }
}
