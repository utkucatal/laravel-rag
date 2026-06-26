<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JsonMachine\Items;
use Throwable;

class ImportStreamController
{
    private const int CHUNK_SIZE = 1000;

    public function __invoke(Request $request): JsonResponse
    {
        $path = $request->input('file', base_path('catalog.json'));

        if (! is_string($path) || ! is_readable($path)) {
            return response()->json(['status' => 'error', 'message' => "File not readable: {$path}"], 400);
        }

        $chunk = [];
        $imported = 0;

        try {
            foreach (Items::fromFile($path) as $row) {
                $chunk[] = [
                    'url'          => $row->url ?? null,
                    'product_id'   => $row->id ?? null,
                    'title'        => $row->title ?? '',
                    'manufacturer' => $row->manufacturer ?? null,
                    'oem_pn'       => $row->oem_pn ?? null,
                    'condition'    => $row->condition ?? null,
                    'price_eur'    => $row->price_eur ?? null,
                    'weight_kg'    => $row->weight_kg ?? null,
                    'category'     => $row->category ?? null,
                    'description'  => $row->description ?? null,
                ];

                if (count($chunk) >= self::CHUNK_SIZE) {
                    $imported += $this->flush($chunk);
                    $chunk = [];
                }
            }

            if (! empty($chunk)) {
                $imported += $this->flush($chunk);
            }
        } catch (Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'imported' => $imported], 400);
        }

        return response()->json(['status' => 'ok', 'imported' => $imported]);
    }

    /**
     * @throws Throwable
     */
    private function flush(array $chunk): int
    {
        try {
            return DB::transaction(function () use ($chunk) {
                Product::query()->upsert($chunk, ['url']);

                return count($chunk);
            });
        } catch (Throwable $e) {
            report($e);

            throw new \RuntimeException('Chunk insert failed: '.$e->getMessage(), previous: $e);
        }
    }
}
