<?php

namespace App\Services\Llm;

use Illuminate\Support\Facades\Http;

class AnthropicClient
{
    public function message(string $system, string $prompt, int $maxTokens = 1024): string
    {
        $response = Http::withHeaders([
            'x-api-key'         => config('services.anthropic.key'),
            'anthropic-version' => '2023-06-01',
        ])
            ->asJson()
            ->post('https://api.anthropic.com/v1/messages', [
                'model'      => config('rag.gen_model'),
                'max_tokens' => $maxTokens,
                'system'     => $system,
                'messages'   => [['role' => 'user', 'content' => $prompt]],
            ])
            ->throw()
            ->json();

        return collect($response['content'])->firstWhere('type', 'text')['text'];
    }
}
