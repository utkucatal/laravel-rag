<?php

namespace App\Importing\Concerns;

// Shared row mapping so every importer builds the same product shape.
trait NormalizesProductRow
{
    protected function normalizeRow(object $row): array
    {
        return [
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
    }
}
