<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class RagFetch extends Command
{
    protected $signature = 'rag:fetch';
    protected $description = 'Fetch product pages into storage/app/pages';

    public function handle(): int
    {
        $dir = storage_path('app/pages');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $urls = $this->collectProductUrls();

        foreach (array_chunk($urls, 10) as $batch) {
            $responses = Http::pool(fn ($pool) => array_map(
                fn ($url) => $pool->as($url)->get($url), $batch
            ));

            foreach ($responses as $url => $response) {
                $path = storage_path('app/pages/'.md5($url).'.html');
                if (! file_exists($path) && $response->successful()) {
                    file_put_contents($path, $response->body());
                }
            }
        }

        return self::SUCCESS;
    }

    private function collectProductUrls(): array
    {
        return json_decode(
            Storage::get('product_urls.json'), // storage/app/product_urls.json
            true,
            flags: JSON_THROW_ON_ERROR
        );
    }
}
