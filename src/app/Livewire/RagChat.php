<?php

namespace App\Livewire;

use App\Services\Rag\AnswerService;
use App\Services\Rag\RetrievalService;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class RagChat extends Component
{
    public string $question = '';
    public ?string $answer = null;
    public array $results = [];

    public function ask(RetrievalService $retrieval, AnswerService $answers): void
    {
        ['results' => $this->results] = $retrieval->retrieve($this->question);
        $this->answer = $answers->answer($this->question, $this->results);
    }

    public function render(): View
    {
        return view('livewire.rag-chat');
    }
}
