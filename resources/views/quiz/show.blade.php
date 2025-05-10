<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="text-2xl font-bold leading-tight text-gray-800">
                {{ $quiz->title }}
            </h2>

            <div class="flex items-center space-x-2">
                <p class="text-sm text-gray-500">By: 
                    <a href="{{ route('profile.show', ['profile' => $quiz->user->id, 'tab' => 'quizzes']) }}" class="hover:underline">
                        {{ $quiz->user->name }}
                    </a>
                </p>
                {{-- 
                <a href="{{ route('profile.show', ['profile' => $quiz->user->id, 'tab' => 'flashcards']) }}">
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
                --}} 
            </div>
    
            @if ($quiz->user_id != auth()->id())
                <!-- Favorite Button Under Author -->
                <div 
                    x-data="favoriteComponent({{ $quiz->id }}, '{{ $quiz->favorites->contains('user_id', auth()->id()) ? 'true' : 'false' }}', '{{ $quiz->favorites->count() }}', 'quiz')" 
                    class="absolute right-2"
                >
                    <form @submit.prevent="toggle">
                        <button type="submit" x-text="favorited ? '❤️' : '🤍'" class="text-xl"></button>
                    </form>
        
                </div>
            @endif
            @if ($quiz->user_id === auth()->id())
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
                            <x-dropdown-link :href="route('quiz.edit', $quiz)">
                                ✏️ Edit
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('quiz.destroy', $quiz) }}" onsubmit="return confirm('Are you sure you want to delete this quiz?');">
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
    </x-slot>

    <div class="max-w-4xl mx-auto mt-6 p-6 bg-white shadow rounded">

        <form method="POST" action="{{ route('quiz.submit', $quiz) }}">
            @csrf

            @foreach ($quiz->questions as $index => $question)
                <div class="mb-6 p-4 bg-gray-100 rounded shadow-sm">
                    <h3 class="font-semibold mb-2">Question {{ $index + 1 }}: {{ $question->question }}</h3>

                    <ul class="space-y-2">
                        @foreach ($question->answers as $answer)
                            <li class="flex items-center">
                                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $answer->id }}" id="answer-{{ $answer->id }}" class="mr-2">
                                <label for="answer-{{ $answer->id }}">{{ $answer->answer }}</label>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
            
            <div class="text-right">
                <x-primary-button>Submit Answers</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>

<script>
    // Similar to a useEffect
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