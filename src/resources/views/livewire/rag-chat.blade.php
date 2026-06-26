<div class="max-w-2xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-1">🔧 Industrial Product RAG</h1>
    <p class="text-gray-500 mb-6">Answers are based on the 388 products in the catalog.</p>

    <form wire:submit.prevent="ask" class="flex gap-2 mb-6">
        <input
            type="text"
            wire:model="question"
            placeholder="örn. Siemens motors under €2000 / vacuum pumps over 300 kg"
            class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
        <button
            type="submit"
            class="bg-blue-600 text-white rounded-lg px-5 py-2 font-medium hover:bg-blue-700 disabled:opacity-50"
            wire:loading.attr="disabled"
        >
            <span wire:loading.remove wire:target="ask">Ask</span>
            <span wire:loading wire:target="ask">Searching…</span>
        </button>
    </form>

    @if ($answer !== null)
        <div class="bg-white rounded-lg shadow p-5 mb-4">
            <div class="whitespace-pre-wrap text-gray-800">{{ $answer }}</div>
        </div>

        <details class="bg-white rounded-lg shadow p-4">
            <summary class="cursor-pointer font-medium text-gray-700">
                🔎 Retrieval details — {{ count($results) }} products
            </summary>
            <ul class="mt-3 space-y-1 text-sm">
                @foreach ($results as $r)
                    <li class="flex items-center gap-2">
                        <span class="font-mono bg-gray-100 rounded px-2 py-0.5">
                            {{ number_format($r->sim, 2) }}
                        </span>
                        <a href="{{ $r->url }}" target="_blank" class="text-blue-600 hover:underline">
                            {{ $r->title }}
                        </a>
                    </li>
                @endforeach
                @if (empty($results))
                    <li class="text-gray-400">No products above the threshold — Claude not called.</li>
                @endif
            </ul>
        </details>
    @endif
</div>
