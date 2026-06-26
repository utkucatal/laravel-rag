<?php

namespace App\Importing;

use App\Contracts\Importer;
use JsonMachine\Items;
use RuntimeException;

// Streams a JSON file and inserts it in chunks; subclasses decide mapping and persistence.
abstract class AbstractJsonImporter implements Importer
{
    protected int $chunkSize = 1000;

    public function import(string $path): int
    {
        if (! is_readable($path)) {
            throw new RuntimeException("File not readable: {$path}");
        }

        $chunk = [];
        $total = 0;

        foreach (Items::fromFile($path) as $row) {
            $chunk[] = $this->mapRow($row);

            if (count($chunk) >= $this->chunkSize) {
                $total += $this->persist($chunk);
                $chunk = [];
            }
        }

        if (! empty($chunk)) {
            $total += $this->persist($chunk);
        }

        return $total;
    }

    // Turn a raw row into a DB-ready array.
    abstract protected function mapRow(object $row): array;

    // Write a chunk and return how many rows were saved.
    abstract protected function persist(array $chunk): int;
}
