<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold leading-tight text-gray-800">
            {{ $profile->name }}
        </h2>
    </x-slot>
    <div class="max-w-7xl mx-auto py-6 space-y-8">

        <!-- LOGGED IN Section -->
        <!-- Quizzes Section -->
        <div>
            <h3 class="text-xl font-semibold mb-4">Quizzes</h3>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($quizzes as $quiz)
                    <div class="relative bg-white shadow-md rounded-lg p-4">
                        <h4 class="text-lg font-semibold mb-2">{{ $quiz->title }}</h4>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('quiz.show', $quiz) }}" class="text-blue-600 hover:underline">Take</a>
                            @if ($quiz->user_id === auth()->id())
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
                            @endif
                        </div>
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
                    <div class="relative bg-white shadow-md rounded-lg p-4">
                        <h4 class="text-lg font-semibold mb-2">{{ $flashcardSet->title }}</h4>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('flashcardSet.show', $flashcardSet) }}" class="text-blue-600 hover:underline">View Set</a>
                            @if ($flashcardSet->user_id === auth()->id())
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
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">No flashcard sets found.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>