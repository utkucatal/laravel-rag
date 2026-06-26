<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JsonMachine\Items;
use Throwable;

class ImportCopyController
{
    private const array COLUMNS = ['url', 'product_id', 'title', 'manufacturer', 'oem_pn', 'condition', 'price_eur', 'weight_kg', 'category', 'description'];

    private const int BATCH = 5000;

    public function __invoke(Request $request): JsonResponse
    {
        $path = $request->input('file', base_path('catalog.json'));

        if (! is_string($path) || ! is_readable($path)) {
            return response()->json(['status' => 'error', 'message' => "File not readable: {$path}"], 400);
        }

        /** @var \Pdo\Pgsql $pdo */
        $pdo = DB::connection()->getPdo();
        $cols = implode(', ', self::COLUMNS);

        try {
            $pdo->beginTransaction();
            $pdo->exec('CREATE TEMP TABLE products_stage (LIKE products INCLUDING DEFAULTS) ON COMMIT DROP');

            $rows = [];
            foreach (Items::fromFile($path) as $row) {
                $rows[] = $this->line($row);

                if (count($rows) >= self::BATCH) {
                    $pdo->copyFromArray('products_stage', $rows, "\t", '\\\\N', $cols);
                    $rows = [];
                }
            }

            if (! empty($rows)) {
                $pdo->copyFromArray('products_stage', $rows, "\t", '\\\\N', $cols);
            }

            $imported = $pdo->exec("INSERT INTO products ({$cols}) SELECT {$cols} FROM products_stage ON CONFLICT (url) DO NOTHING");
            $pdo->commit();
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }

        return response()->json(['status' => 'ok', 'imported' => $imported]);
    }

    private function line(object $row): string
    {
        $map = [
            $row->url ?? null, $row->id ?? null, $row->title ?? '', $row->manufacturer ?? null,
            $row->oem_pn ?? null, $row->condition ?? null, $row->price_eur ?? null,
            $row->weight_kg ?? null, $row->category ?? null, $row->description ?? null,
        ];

        return implode("\t", array_map(function ($v) {
            return $v === null
                ? '\\N'
                : str_replace(["\\", "\t", "\n", "\r"], ['\\\\', '\\t', '\\n', '\\r'], (string) $v);
        }, $map));
    }
}
