<?php


namespace App\Services\Rag;

use App\Services\Llm\AnthropicClient;
use Throwable;

class FilterExtractor
{
    private const SYSTEM = <<<'TXT'
    Extract structured search filters from the user's product query and return ONLY this JSON
    (no markdown, no prose):
    {"semantic_query": str, "manufacturer": str|null, "category": str|null,
     "max_price": number|null, "min_weight": number|null}
    manufacturer: brand name or null. category: product-type keyword or null.
    max_price: euro ceiling or null. min_weight: kg floor or null.
    semantic_query: what's left of the query for semantic search once filters are pulled out.
    TXT;

    private const EMPTY = [
        'semantic_query' => null, 'manufacturer' => null,
        'category' => null, 'max_price' => null, 'min_weight' => null,
    ];

    public function __construct(private AnthropicClient $claude)
    {
    }

    public function extract(string $query): array
    {
        try {
            $text = trim($this->claude->message(self::SYSTEM, $query, maxTokens: 300));
            $text = preg_replace('/^```(json)?|```$/m', '', $text);
            return [...self::EMPTY, ...json_decode(trim($text), true, flags: JSON_THROW_ON_ERROR)];
        } catch (Throwable) {
            return [...self::EMPTY, 'semantic_query' => $query];   // fallback: pure semantic
        }
    }
}
