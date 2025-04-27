<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold leading-tight text-gray-800">
            Find Study Material
        </h2>
    </x-slot>

    <!-- Search Bar -->
    <div class="flex justify-center mt-6">
        <form action="{{ route('finder.search') }}" method="GET" class="w-full max-w-xl flex items-center bg-white border border-gray-300 rounded-lg shadow-sm px-4 py-2">
            <input
                type="text"
                name="search"
                placeholder="Search for quizzes or flashcard sets..."
                value="{{ request('search') }}"
                class="flex-grow focus:outline-none focus:ring-0 text-gray-700 placeholder-gray-400 bg-transparent"
            />
            <button
                type="submit"
                class="ml-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-md transition duration-150"
            >
                Search
            </button>
        </form>
    </div>

    <div class="max-w-7xl mx-auto py-6 space-y-8" x-data="{ tab: 'quizzes' }">

        <!-- Tabs Navigation -->
        <div class="flex space-x-4 mb-6 border-b justify-center">
            <button 
                @click="tab = 'quizzes'" 
                :class="tab === 'quizzes' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-600'" 
                class="pb-2 font-medium focus:outline-none"
            >
                Quizzes
            </button>

            <button 
                @click="tab = 'flashcards'" 
                :class="tab === 'flashcards' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-600'" 
                class="pb-2 font-medium focus:outline-none"
            >
                Flashcards
            </button>
        </div>

        <!-- Quizzes Section -->
        <div x-show="tab === 'quizzes'">
            <h3 class="text-xl font-semibold mb-4">Quizzes</h3>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($quizzes as $quiz)
                    <div class="relative bg-white shadow-md rounded-lg p-4">
                        <h4 class="text-lg font-semibold mb-2">{{ $quiz->title }}</h4>

                        <div class="flex items-center space-x-2">
                            <p class="text-sm text-gray-500">By: 
                                <a href="{{ route('profile.show', ['profile' => $quiz->user->id, 'tab' => 'quizzes']) }}" class="hover:underline">
                                    {{ $quiz->user->name }}
                                </a>
                            </p>
                            <a href="{{ route('profile.show', ['profile' => $quiz->user->id, 'tab' => 'quizzes']) }}">
                                @if ($quiz->user->profile_picture)
                                    <img src="{{ asset('storage/' . $quiz->user->profile_picture) }}" 
                                        alt="Profile Picture" 
                                        class="rounded-full w-10 h-10 object-cover">
                                @else
                                    <img src="{{ asset('images/default_profile.png') }}" 
                                        alt="Default Picture" 
                                        class="rounded-full w-10 h-10 object-cover">
                                @endif
                            </a>
                        </div>

                        <div class="flex justify-end mt-4">
                            <a href="{{ route('quiz.take', $quiz) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-sm font-medium transition">
                                Take Quiz
                            </a>
                        </div>

                        <!-- Favorite -->
                        <div 
                            x-data="favoriteComponent({{ $quiz->id }}, '{{ $quiz->favorites->contains('user_id', auth()->id()) ? 'true' : 'false' }}', '{{ $quiz->favorites->count() }}', 'quiz')" 
                            class="absolute top-2 right-2"
                        >
                            <form @submit.prevent="toggle">
                                <button type="submit" x-text="favorited ? '❤️' : '🤍'"></button>
                            </form>
                        
                            <span class="text-xs text-gray-500 block mt-2" x-text="`❤️ ${count}`"></span>
                        </div>

                    </div>
                @empty
                    <p class="text-gray-500">No quizzes found.</p>
                @endforelse
            </div>
        </div>

        <!-- Flashcards Section -->
        <div x-show="tab === 'flashcards'">
            <h3 class="text-xl font-semibold mb-4">Flashcard Sets</h3>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($flashcardSets as $flashcardSet)
                    <div class="relative bg-white shadow-md rounded-lg p-4">
                        <h4 class="text-lg font-semibold mb-2">{{ $flashcardSet->title }}</h4>

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

                        <div class="flex justify-end mt-4">
                            <a href="{{ route('flashcardSet.show', $flashcardSet) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-sm font-medium transition">
                                View Set
                            </a>
                        </div>

                        <!-- Favorite -->
                        <div 
                            x-data="favoriteComponent({{ $flashcardSet->id }}, '{{ $flashcardSet->favorites->contains('user_id', auth()->id()) ? 'true' : 'false' }}', '{{ $flashcardSet->favorites->count() }}', 'flashcard')" 
                            class="absolute top-2 right-2"
                        >
                            <form @submit.prevent="toggle">
                                <button type="submit" x-text="favorited ? '❤️' : '🤍'"></button>
                            </form>
                        
                            <span class="text-xs text-gray-500 block mt-2" x-text="`❤️ ${count}`"></span>
                        </div>


                    </div>
                @empty
                    <p class="text-gray-500">No flashcard sets found.</p>
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
                }
            }
        };
    }
</script>
