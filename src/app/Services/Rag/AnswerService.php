<?php

namespace App\Services\Rag;

use App\Services\Llm\AnthropicClient;

class AnswerService
{
    private const SYSTEM = <<<'TXT'
    You are an industrial product catalog assistant. Answer ONLY from the PRODUCTS provided in the
    message. If none are relevant, say "I couldn't find a relevant product in the catalog."
    Never invent products or specs. Politely refuse general chat. Always show the id and url of every
    product you use.
    TXT;

    public function __construct(private AnthropicClient $claude) {}

    public function answer(string $query, array $results): string
    {
        if (empty($results)) {
            return "I couldn't find a relevant product in the catalog.";
        }

        return $this->claude->message(self::SYSTEM, $this->buildPrompt($query, $results));
    }

    public function buildPrompt(string $query, array $results): string
    {
        $blocks = collect($results)->map(function ($row, $i) {
            $desc = mb_substr($row->description ?? '', 0, 1500);

            return "[".($i + 1)."] {$row->title}\n"
                ."  id: {$row->product_id} | manufacturer: {$row->manufacturer} | OEM: {$row->oem_pn}\n"
                ."  price: €{$row->price_eur} | weight: {$row->weight_kg} kg | category: {$row->category}\n"
                ."  url: {$row->url}\n  description: {$desc}";
        })->implode("\n\n");

        return "PRODUCTS:\n{$blocks}\n\nQUESTION: {$query}";
    }
}
