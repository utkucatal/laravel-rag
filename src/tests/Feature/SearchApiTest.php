<?php

namespace Tests\Feature;

use App\Services\Rag\RetrievalService;
use Tests\TestCase;

class SearchApiTest extends TestCase
{
    public function test_search_requires_a_query(): void
    {
        $this->getJson('/api/search')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('q');
    }

    public function test_ask_returns_not_found_when_no_match(): void
    {
        // RetrievalService'i mock → empty results → "Not found" without calling the LLM
        $this->mock(RetrievalService::class)
            ->shouldReceive('retrieve')->andReturn(['filters' => [], 'results' => []]);

        $this->postJson('/api/ask', ['question' => 'hydraulic excavator'])
            ->assertOk()
            ->assertJsonPath('answer', "I couldn't find a relevant product in the catalog.");
    }
}
