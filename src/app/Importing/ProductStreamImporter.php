<?php

namespace App\Importing;

use App\Importing\Concerns\NormalizesProductRow;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

// Streams a JSON file into the products table, upserting on url.
class ProductStreamImporter extends AbstractJsonImporter
{
    use NormalizesProductRow;

    protected function mapRow(object $row): array
    {
        return $this->normalizeRow($row);
    }

    protected function persist(array $chunk): int
    {
        return DB::transaction(function () use ($chunk) {
            Product::query()->upsert($chunk, ['url']);

            return count($chunk);
        });
    }
}
