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
    

    <div class="max-w-7xl mx-auto py-6 space-y-8">
        <!-- Quizzes Section -->
        <div>
            <h3 class="text-xl font-semibold mb-4">Quizzes</h3>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($quizzes as $quiz)
                    <div class="bg-white shadow-md rounded-lg p-4">
                        <h4 class="text-lg font-semibold mb-2">{{ $quiz->title }}</h4>
                        <a href="{{ route('profile.show', $quiz->user->id) }}" class="text-sm text-gray-500">By: {{ $quiz->user->name }}</a>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('quiz.take', $quiz) }}" class="text-blue-600 hover:underline">Take</a>
                        </div>

                        <form action="{{ route('favorite.toggle', ['type' => 'quiz', 'id' => $quiz->id]) }}" method="POST">
                            @csrf
                            <button type="submit">
                                @if($quiz->favorites->contains('user_id', auth()->id()))
                                    ❤️
                                @else
                                    🤍
                                @endif
                            </button>
                        </form>

                        <span class="text-xs text-gray-500">
                            {{ $quiz->favorites->count() }} {{ Str::plural('Favorite', $quiz->favorites->count()) }}
                        </span>

                    </div>

                @empty
                    <p class="text-gray-500">No quizzes found.</p>
                @endforelse
            </div>
        </div>

        <!-- Flashcards Section -->
        <div>
            <h3 class="text-xl font-semibold mb-4">Flashcard Sets</h3>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($flashcardSets as $flashcardSet)
                    <div class="bg-white shadow-md rounded-lg p-4">
                        <h4 class="text-lg font-semibold mb-2">{{ $flashcardSet->title }}</h4>
                        <a href="{{ route('profile.show', $flashcardSet->user->id) }}" class="text-sm text-gray-500">By: {{ $flashcardSet->user->name }}</a>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('flashcardSet.show', $flashcardSet) }}" class="text-blue-600 hover:underline">View</a>
                        </div>

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

                        <span class="text-xs text-gray-500">
                            {{ $flashcardSet->favorites->count() }} {{ Str::plural('Favorite', $flashcardSet->favorites->count()) }}
                        </span>

                    </div>
                @empty
                    <p class="text-gray-500">No flashcard sets found.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>