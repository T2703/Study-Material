@vite('resources/css/flashcard.css')

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            {{ $flashcardSet->title }}
            <br>
            <a href="{{ route('profile.show', $flashcardSet->user->id) }}" class="text-sm text-gray-500">By: {{ $flashcardSet->user->name }}</a>
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto mt-6 p-6 bg-white shadow rounded">
        <p class="mb-4 text-gray-700">
            <strong>Description:</strong> {{ $flashcardSet->description ?? 'No description provided.' }}
        </p>

        <div class="relative max-w-xl mx-auto">
            <div id="flashcardSlider" class="overflow-hidden">
                <div id="flashcardSlides" class="flex transition-transform duration-200" style="width: {{ count($flashcardSet->flashcards) * 100 }}%">
                    @foreach ($flashcardSet->flashcards as $index => $card)
                        <div class="w-full flex-shrink-0 p-4">
                            <div class="flashcard relative w-full min-h-[200px] cursor-pointer rounded shadow-sm perspective" onclick="this.classList.toggle('is-flipped')">
                                <div class="flashcard-inner">
                                    <div class="flashcard-front">
                                        <h2 class="text-lg font-semibold">Q:</h2>
                                        <p>{{ $card->question }}</p>
                                    </div>
                                    <div class="flashcard-back">
                                        <h2 class="text-lg font-semibold">A:</h2>
                                        <p>{{ $card->answer }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        
            <!-- Navigation Buttons -->
            <div class="flex justify-between mt-4">
                <button onclick="changeCard(-1)" class="text-blue-600 hover:underline">← Previous</button>
                <button onclick="changeCard(1)" class="text-blue-600 hover:underline">Next →</button>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto mt-6 p-6 bg-white shadow rounded space-y-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Study Mode: All Flashcards</h2>
    
        @foreach ($flashcardSet->flashcards as $index => $card)
            <div class="border rounded p-4 bg-gray-50 shadow-sm">
                <h3 class="font-semibold text-gray-700">Q{{ $index + 1 }}: {{ $card->question }}</h3>
                <p class="mt-2 text-blue-700"><strong>A:</strong> {{ $card->answer }}</p>
            </div>
        @endforeach
    </div>
    
</x-app-layout>

<script>
    let currentCardIndex = 0;
    const totalCards = {{ count($flashcardSet->flashcards) }};
    const slides = document.getElementById('flashcardSlides');

    function changeCard(direction) {
        currentCardIndex += direction;

        // loop around
        if (currentCardIndex < 0) currentCardIndex = totalCards - 1;
        if (currentCardIndex >= totalCards) currentCardIndex = 0;

        slides.style.transform = `translateX(-${currentCardIndex * 100}%)`;
    }

    // Arrow key navigation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowRight') changeCard(1);
        if (e.key === 'ArrowLeft') changeCard(-1);
    });
</script>
