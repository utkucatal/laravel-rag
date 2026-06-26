<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;

class RagParse extends Command
{
    protected $signature = 'rag:parse';
    protected $description = 'Parse cached HTML into catalog.json';

    public function handle(): int
    {
        $files = glob(storage_path('app/pages/*.html'));

        if (empty($files)) {
            $this->error('storage/app/pages empty. first rag:fetch');

            return self::FAILURE;
        }

        $products = [];

        foreach ($files as $file) {
            $crawler = new Crawler(file_get_contents($file));

            $title = $this->og($crawler, 'og:title');
            $url   = $this->og($crawler, 'og:url');

            // <div class="apc-product-description">
            $descNode = $crawler->filter('.apc-product-description');
            $description = $descNode->count()
                ? trim(preg_replace('/\s+/', ' ', $descNode->text('')))
                : '';

            $products[] = [
                'url'          => $url,
                'id'           => $this->slugId($url),
                'title'        => $title,
                'manufacturer' => $this->match('/from manufacturer\s+([A-Z][\w.\- ]{1,40}?)\s+with/i', $description)
                                    ?? $this->brandFromTitle($title),
                'oem_pn'       => $this->match('/(?:part number|type number)\s+([\w.\-\/]+)/i', $description),
                'condition'    => $this->match('/Condition:\s*([A-Za-z ]{2,20}?)\s+Product/i', $description),
                'price_eur'    => $this->price($crawler),
                'weight_kg'    => (float) $this->match('/(?:weighs|weight of)\s+([\d.]+)\s*kg/i', $description) ?: null,
                'category'     => $this->category($crawler),
                'description'  => $description,
            ];
        }

        $products = array_values(array_filter($products, fn ($p) => $p['url'] && $p['title']));

        file_put_contents(
            base_path('catalog.json'),
            json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );

        $this->info('Parsed '.count($products).' products -> catalog.json');

        return self::SUCCESS;
    }

    // <meta property="og:..." content="...">
    private function og(Crawler $crawler, string $property): ?string
    {
        $node = $crawler->filter('meta[property="'.$property.'"]');

        return $node->count() ? trim($node->attr('content')) : null;
    }

    // <meta itemprop="price" content="553.35">
    private function price(Crawler $crawler): ?float
    {
        $node = $crawler->filter('meta[itemprop="price"]');

        return $node->count() ? (float) $node->attr('content') : null;
    }

    // "Mechanical > Gears > Straight-toothed"
    private function category(Crawler $crawler): ?string
    {
        $parts = $crawler->filter('a.breadcrumb-link')
            ->each(fn (Crawler $a) => trim($a->attr('title') ?? ''));

        $parts = array_values(array_filter($parts, fn ($p) => $p !== '' && strcasecmp($p, 'Home') !== 0));

        return $parts ? implode(' > ', $parts) : null;
    }

    // url -> id (.../kolbus-12523327-gear -> kolbus-12523327-gear)
    private function slugId(?string $url): ?string
    {
        return $url ? basename(parse_url($url, PHP_URL_PATH)) : null;
    }

    // regex or null
    private function match(string $pattern, string $text): ?string
    {
        return preg_match($pattern, $text, $m) ? trim($m[1]) : null;
    }

    //The brand is usually the first word of the title: "Kolbus 12523327 Gear" -> "Kolbus"
    private function brandFromTitle(?string $title): ?string
    {
        if (! $title) {
            return null;
        }

        $first = explode(' ', trim($title))[0];

        return ctype_alpha($first) && strlen($first) > 1 ? $first : null;
    }
}
