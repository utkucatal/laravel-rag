<?php


namespace App\Services\Rag;

use App\Services\Llm\VoyageClient;

class EmbeddingService
{
    public function __construct(private VoyageClient $voyage)
    {
    }

    public function embedDocuments(array $texts): array
    {
        return $this->voyage->embed($texts, 'document');
    }

    public function embedQuery(string $text): array
    {
        return $this->voyage->embed([$text], 'query')[0];
    }

    // pgvector literal: [0.1,0.2,...]
    public static function toVector(array $vec): string
    {
        return '[' . implode(',', $vec) . ']';
    }
}
