<?php

namespace App\Contracts;

interface Importer
{
    /** Returns the number of imported rows. */
    public function import(string $path): int;
}
