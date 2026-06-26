<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\Rag\EmbeddingService;
use Illuminate\Console\Command;

class RagIndex extends Command
{
    protected $signature = 'rag:index';
    protected $description = 'Embed catalog.json into pgvector';

    public function handle(EmbeddingService $embeddings): int
    {
        $products = json_decode(
            file_get_contents(base_path('catalog.json')), true, flags: JSON_THROW_ON_ERROR
        );

        $chunks = array_chunk($products, 130);
        $last = count($chunks) - 1;

        foreach ($chunks as $c => $chunk) {
            $models = array_map(fn ($p) => new Product($this->mapRow($p)), $chunk);
            $vectors = $embeddings->embedDocuments(array_map(fn ($m) => $m->searchableText(), $models));

            $rows = [];
            foreach ($chunk as $i => $p) {
                $rows[] = array_merge(
                    $this->mapRow($p),
                    ['embedding' => EmbeddingService::toVector($vectors[$i])],
                );
            }

            Product::upsert($rows, uniqueBy: ['url']);
            $this->info('Indexed '.count($chunk).' (chunk '.($c + 1).'/'.count($chunks).')');

            // Voyage free account: 3 RPM. 20sec wait.
            if ($c < $last) {
                $this->comment('waiting 20 sec for rate limit...');
                sleep(21);
            }
        }

        return self::SUCCESS;
    }

    private function mapRow(array $p): array
    {
        return [
            'url'          => $p['url'],
            'product_id'   => $p['id'] ?? null,
            'title'        => $p['title'] ?? '',
            'manufacturer' => $p['manufacturer'] ?? null,
            'oem_pn'       => $p['oem_pn'] ?? null,
            'condition'    => $p['condition'] ?? null,
            'price_eur'    => $p['price_eur'] ?? null,
            'weight_kg'    => $p['weight_kg'] ?? null,
            'category'     => $p['category'] ?? null,
            'description'  => $p['description'] ?? null,
        ];
    }
}
