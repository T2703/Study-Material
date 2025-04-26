<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold leading-tight text-gray-800">
            Dashboard
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 space-y-8">

        <!-- LOGGED IN Section -->
        <!-- Quizzes Section -->
        <div>
            <h3 class="text-xl font-semibold mb-4">Your Quizzes</h3>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($quizzes as $quiz)
                    <div class="relative bg-white shadow-md rounded-lg p-4">
                        <!-- Dropdown Trigger -->
                        <div class="absolute top-2 right-2">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="text-gray-500 hover:text-gray-700 focus:outline-none">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3z"/>
                                        </svg>
                                    </button>
                                </x-slot>
            
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('quiz.edit', $quiz)">
                                        ✏️ Edit
                                    </x-dropdown-link>
                                    <form method="POST" action="{{ route('quiz.destroy', $quiz) }}" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                            🗑️ Delete
                                        </button>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
            
                        <!-- Quiz Title & Action -->
                        <h4 class="text-lg font-semibold mb-2">{{ $quiz->title }}</h4>
                        <div class="flex justify-end mt-4">
                            <a href="{{ route('quiz.take', $quiz) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-sm font-medium transition">
                                Take Quiz
                            </a>
                        </div>       
                    </div>
                @empty
                    <p class="text-gray-500">No quizzes found.</p>
                @endforelse

                @if ($totalQuizzes > 5)
                    <div class="relative bg-white shadow-md rounded-lg p-4">
                        <div class="flex justify-center mt-4">
                            <a href="{{ route('profile.show', ['profile' => auth()->id(), 'tab' => 'quizzes']) }}"
                            class="text-indigo-600 hover:underline text-sm font-medium">
                                View All Quizzes →
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Flashcards Section -->
        <div>
            <h3 class="text-xl font-semibold mb-4">Your Flashcard Sets</h3>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($flashcardSets as $flashcardSet)
                    <div class="relative bg-white shadow-md rounded-lg p-4">
                        <h4 class="text-lg font-semibold mb-2">{{ $flashcardSet->title }}</h4>
            
                        <div class="flex justify-end mt-4">
                            <a href="{{ route('flashcardSet.show', $flashcardSet) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-sm font-medium transition">
                                View Set
                            </a>
                        </div>
            
                        <!-- Dropdown Menu -->
                        <div class="absolute top-2 right-2">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="text-gray-500 hover:text-gray-700 focus:outline-none">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3z"/>
                                        </svg>
                                    </button>
                                </x-slot>
        
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('flashcardSet.edit', $flashcardSet)">
                                        ✏️ Edit
                                    </x-dropdown-link>
        
                                    <form method="POST" action="{{ route('flashcardSet.destroy', $flashcardSet) }}" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                            🗑️ Delete
                                        </button>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">No flashcard sets found.</p>
                @endforelse

                @if ($totalFlashcardSets > 5)
                    <div class="relative bg-white shadow-md rounded-lg p-4">
                        <div class="flex justify-center mt-4">
                            <a href="{{ route('profile.show', ['profile' => auth()->id(), 'tab' => 'flashcards']) }}"
                            class="text-indigo-600 hover:underline text-sm font-medium">
                                View All Flashcards →
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>


        <!-- Favorites Section -->
        <!-- Quizzes Section -->
        <div>
            <h3 class="text-xl font-semibold mb-4">Favorite Quizzes</h3>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($favoriteQuizzes as $favoriteQuiz)
                    <div x-favorite-card class="relative bg-white shadow-md rounded-lg p-4">
                        <h4 class="text-lg font-semibold mb-2">{{ $favoriteQuiz->title }}</h4>
                        <a href="{{ route('profile.show', $favoriteQuiz->user->id) }}" class="text-sm text-gray-500">By: {{ $favoriteQuiz->user->name }}</a>
                        <div class="flex justify-end mt-4">
                            <a href="{{ route('quiz.take', $favoriteQuiz) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-sm font-medium transition">
                                Take Quiz
                            </a>
                        </div>    
                        
                        <span class="text-xs text-gray-500 block mt-2">
                            ❤️ {{ $favoriteQuiz->favorites->count() }}
                        </span>

                        <div class="absolute top-2 right-2">
                            <!-- Favorite  -->
                            <div 
                                x-data="favoriteComponent({{ $favoriteQuiz->id }}, '{{ $favoriteQuiz->favorites->contains('user_id', auth()->id()) ? 'true' : 'false' }}', '{{ $favoriteQuiz->favorites->count() }}', 'quiz')" 
                                class="mt-2"
                            >
                                <form @submit.prevent="toggle">
                                    <button type="submit" x-text="favorited ? '❤️' : '🤍'" class="text-xl"></button>
                                </form>

                            </div>
                        </div>

                    </div>

                @empty
                    <p class="text-gray-500">No quizzes found.</p>
                @endforelse
                @if ($totalFavoriteQuizzes > 5)
                    <div class="relative bg-white shadow-md rounded-lg p-4">
                        <div class="flex justify-center mt-4">
                            <a href="{{ route('favorite.index', ['profile' => auth()->id(), 'tab' => 'quizzes']) }}"
                            class="text-indigo-600 hover:underline text-sm font-medium">
                                View All Favorite Quizzes →
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Flashcards Section -->
        <div>
            <h3 class="text-xl font-semibold mb-4">Favorite Flashcard Sets</h3>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($favoriteFlashcardSets as $favoriteFlashcardSet)
                    <div x-favorite-card class="relative bg-white shadow-md rounded-lg p-4">
                        <h4 class="text-lg font-semibold mb-2">{{ $favoriteFlashcardSet->title }}</h4>
                        <a href="{{ route('profile.show', $favoriteFlashcardSet->user->id) }}" class="text-sm text-gray-500">By: {{ $favoriteFlashcardSet->user->name }}</a>
                        <div class="flex justify-end mt-4">
                            <a href="{{ route('flashcardSet.show', $favoriteFlashcardSet) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-sm font-medium transition">
                                View Set
                            </a>
                        </div>

                        <span class="text-xs text-gray-500 block mt-2">
                            ❤️ {{ $favoriteFlashcardSet->favorites->count() }}
                        </span>
                        
                        <div class="absolute top-2 right-2">

                            <div class="absolute top-2 right-2">
                                <!-- Favorite  -->
                                <div 
                                    x-data="favoriteComponent({{ $favoriteFlashcardSet->id }}, '{{ $favoriteFlashcardSet->favorites->contains('user_id', auth()->id()) ? 'true' : 'false' }}', '{{ $favoriteFlashcardSet->favorites->count() }}', 'flashcard')" 
                                    class="mt-2"
                                >
                                    <form @submit.prevent="toggle">
                                        <button type="submit" x-text="favorited ? '❤️' : '🤍'" class="text-xl"></button>
                                    </form>
    
                                </div>
                            </div>
                        </div>

                    </div>
                @empty
                    <p class="text-gray-500">No flashcard sets found.</p>
                @endforelse
                
                @if ($totalFavoriteFlashcardSets > 5)
                    <div class="relative bg-white shadow-md rounded-lg p-4">
                        <div class="flex justify-center mt-4">
                            <a href="{{ route('favorite.index', ['profile' => auth()->id(), 'tab' => 'flashcards']) }}"
                            class="text-indigo-600 hover:underline text-sm font-medium">
                                View All Favorite Flashcard Sets →
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    <!-- Recently Viewed Section -->
    <div>
        <h3 class="text-xl font-semibold mb-4">Recently Viewed</h3>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($recentViews as $view)
                @php $item = $view->viewable; @endphp
                @if ($item)
                    <div class="bg-white shadow-md rounded-lg p-4">
                        <h4 class="text-lg font-semibold mb-2">
                            {{ $item->title }}
                            <span class="ml-2 text-xs text-white bg-gray-600 px-2 py-0.5 rounded">
                                {{ $item instanceof \App\Models\Quiz ? 'Quiz' : 'Flashcards' }}
                            </span>
                        </h4>
                        <a href="{{ route('profile.show', $item->user->id) }}" class="text-sm text-gray-500">
                            By: {{ $item->user->name }}
                        </a>
                        <div class="flex justify-end mt-4">
                            <a href="{{ $item instanceof \App\Models\Quiz ? route('quiz.take', $item) : route('flashcardSet.show', $item) }}" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-sm font-medium transition">
                                {{ $item instanceof \App\Models\Quiz ? 'Take Quiz' : 'View Set' }}
                            </a>
                        </div>   
                    </div>
                @endif
            @empty
                <p class="text-gray-500">No recent views yet.</p>
            @endforelse
        </div>
    </div>

    </div>
</x-app-layout>

<script>
    function favoriteComponent(id, isFavorited, count, type) {
        return {
            favorited: isFavorited === 'true',
            count: parseInt(count),
            async toggle() {
                const response = await fetch(`/favorite/${type}/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    this.favorited = !this.favorited;
                    this.count += this.favorited ? 1 : -1;

                    // Remove the card if unfavorited and in favorites list
                    if (!this.favorited && this.$el.closest('[x-favorite-card]')) {
                        this.$el.closest('[x-favorite-card]').remove();
                    }
                }
            }
        };
    }
</script>