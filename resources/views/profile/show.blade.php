<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold leading-tight text-gray-800">
                {{ $profile->name }}
            </h2>
        
            @if(auth()->id() !== $profile->id)
                <button onclick="openModal()" class="text-sm text-red-600 hover:underline">
                    🚩 Report User
                </button>
            @endif
        </div>
    </x-slot>

    <!-- Search Bar -->
    <div class="flex justify-center mt-6">
        <form action="{{ route('profile.search', ['profile' => $profile->id]) }}" method="GET" class="w-full max-w-xl flex items-center bg-white border border-gray-300 rounded-lg shadow-sm px-4 py-2">
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

    <div class="max-w-7xl mx-auto py-6 space-y-8" x-data="{ tab: '{{ request('tab', 'quizzes') }}' }">

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
                        <div class="flex justify-end mt-4">
                            <a href="{{ route('quiz.take', $quiz) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-sm font-medium transition">
                                Take Quiz
                            </a>
                        </div>
                        
                        @if ($quiz->user_id === auth()->id())
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
                        @else
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
                        @endif
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
                        <div class="flex justify-end mt-4">
                            <a href="{{ route('flashcardSet.show', $flashcardSet) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-sm font-medium transition">
                                View Set
                            </a>
                        </div>

                        @if ($flashcardSet->user_id === auth()->id())
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
                        @else
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
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500">No flashcard sets found.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Report Modal -->
    <div id="reportModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
        <div class="bg-white w-full max-w-md p-6 rounded shadow-lg relative">
            <button onclick="closeModal()" class="absolute top-2 right-3 text-gray-400 hover:text-gray-700 text-xl">&times;</button>
    
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Report {{ $profile->name }}</h2>
    
            <form action="{{ route('report.user', $profile) }}" method="POST">
                @csrf
                <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">Reason (optional)</label>
                <textarea name="reason" id="reason" rows="4" class="w-full border rounded p-2 text-gray-700" placeholder="Explain the issue..."></textarea>
    
                <div class="mt-4 flex justify-end gap-2">
                    <button type="button" onclick="closeModal()" class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded">
                        Cancel
                    </button>
                    <x-primary-button>
                        Submit
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

<script>
    function openModal() {
        document.getElementById('reportModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('reportModal').style.display = 'none';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('reportModal');
        if (event.target === modal) {
            closeModal();
        }
    }

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