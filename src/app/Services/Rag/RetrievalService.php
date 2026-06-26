<?php

namespace App\Services\Rag;

use Illuminate\Support\Facades\DB;

class RetrievalService
{
    public function __construct(
        private FilterExtractor $filters,
        private EmbeddingService $embeddings,
    ) {}

    public function retrieve(string $query): array
    {
        $filter = $this->filters->extract($query);
        $vector = EmbeddingService::toVector(
            $this->embeddings->embedQuery($filter['semantic_query'] ?? $query)
        );

        $where = [];
        $params = [];

        if ($filter['manufacturer']) {
            $where[] = 'manufacturer ILIKE ?';
            $params[] = "%{$filter['manufacturer']}%";
        }
        if ($filter['category']) {
            $where[] = '(category ILIKE ? OR title ILIKE ?)';
            $params[] = "%{$filter['category']}%";
            $params[] = "%{$filter['category']}%";
        }
        if ($filter['max_price'] !== null) {
            $where[] = 'price_eur <= ?';
            $params[] = $filter['max_price'];
        }
        if ($filter['min_weight'] !== null) {
            $where[] = 'weight_kg >= ?';
            $params[] = $filter['min_weight'];
        }
        $clause = $where ? 'WHERE '.implode(' AND ', $where) : '';

        $rows = DB::select("
            SELECT product_id, title, manufacturer, oem_pn, condition, price_eur,
                   weight_kg, category, url, description,
                   1 - (embedding <=> CAST(? AS vector)) AS sim
            FROM products
            {$clause}
            ORDER BY embedding <=> CAST(? AS vector)
            LIMIT ?
        ", [$vector, ...$params, $vector, config('rag.top_k')]);

        $results = array_filter($rows, fn ($row) => $row->sim >= config('rag.min_sim'));

        return ['filters' => $filter, 'results' => array_values($results)];
    }
}
