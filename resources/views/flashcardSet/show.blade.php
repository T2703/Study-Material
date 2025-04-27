@vite('resources/css/flashcard.css')

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold leading-tight text-gray-800">
            {{ $flashcardSet->title }}
        </h2>

        <div class="flex items-center space-x-2">
            <p class="text-sm text-gray-500">By: 
                <a href="{{ route('profile.show', ['profile' => $flashcardSet->user->id, 'tab' => 'flashcards']) }}" class="hover:underline">
                    {{ $flashcardSet->user->name }}
                </a>
            </p>
            <a href="{{ route('profile.show', ['profile' => $flashcardSet->user->id, 'tab' => 'flashcards']) }}">
                @if ($flashcardSet->user->profile_picture)
                    <img src="{{ asset('storage/' . $flashcardSet->user->profile_picture) }}" 
                        alt="Profile Picture" 
                        class="rounded-full w-10 h-10 object-cover">
                @else
                    <img src="{{ asset('images/default_profile.png') }}" 
                        alt="Default Picture" 
                        class="rounded-full w-10 h-10 object-cover">
                @endif
            </a>
        </div>
        
        @if ($flashcardSet->user_id != auth()->id())
            <form action="{{ route('favorite.toggle', ['type' => 'flashcard', 'id' => $flashcardSet->id]) }}" method="POST">
                @csrf
                <button type="submit">
                    @if($flashcardSet->favorites->contains('user_id', auth()->id()))
                        ❤️ 
                    @else
                        🤍
                    @endif
                </button>
            </form>
        @endif
        @if ($flashcardSet->user_id === auth()->id())
            <div class="absolute right-2">
                <x-dropdown width="48">
                    <x-slot name="trigger">
                        <button class="text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3z"/>
                            </svg>
                        </button>
                    </x-slot>
            
                    <x-slot name="content">
                        <x-dropdown-link :href="route('flashcardSet.edit', $flashcardSet)">
                            ✏️ Edit
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('flashcardSet.destroy', $flashcardSet) }}" onsubmit="return confirm('Are you sure you want to delete this quiz?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                🗑️ Delete
                            </button>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        @endif
    </x-slot>

    <div class="max-w-4xl mx-auto mt-6 p-6 bg-white shadow rounded">
        <p class="mb-4 text-gray-700">
            {{ $flashcardSet->description }}
        </p>

        <div class="relative max-w-xl mx-auto">
            <div id="flashcardSlider" class="overflow-hidden">
                <div id="flashcardSlides" class="flex transition-transform duration-200">
                    @foreach ($flashcardSet->flashcards as $index => $card)
                        <div class="w-full flex-shrink-0 p-4">
                            <div class="flashcard relative w-full min-h-[200px] cursor-pointer rounded shadow-sm perspective" onclick="this.classList.toggle('is-flipped')">
                                <div class="flashcard-inner">
                                    <div class="flashcard-front">
                                        <h2 class="text-lg font-semibold"></h2>
                                        <h2>{{ $card->question }}</h2>
                                    </div>
                                    <div class="flashcard-back">
                                        <h2 class="text-lg font-semibold"></h2>
                                        <h2>{{ $card->answer }}</h2>
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

            <!-- Flashcard Counter -->
            <div class="text-center mt-2 text-gray-600 font-medium">
                <span id="cardCounter">1</span> / {{ count($flashcardSet->flashcards) }}
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto mt-80 space-y-6">     
        <h2 class="text-2xl font-bold leading-tight text-gray-800">
            Questions & Answers
        </h2>
        @foreach ($flashcardSet->flashcards as $index => $card)
            <div class="flex flex-col md:flex-row bg-gray-100 rounded-lg shadow-md overflow-hidden">
                
                <!-- Question -->
                <div class="w-full md:w-1/2 p-6 border-b md:border-b-0 md:border-r border-gray-300">
                    <p class="text-gray-800">{{ $card->question }}</p>
                </div>
    
                <!-- Answer -->
                <div class="w-full md:w-1/2 p-6">
                    <p class="text-blue-700">{{ $card->answer }}</p>
                </div>
    
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

        document.getElementById('cardCounter').textContent = currentCardIndex + 1;
    }

    // Arrow key navigation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowRight') changeCard(1);
        if (e.key === 'ArrowLeft') changeCard(-1);
    });

</script>
