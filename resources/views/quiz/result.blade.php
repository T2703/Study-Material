<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            Quiz Results – {{ $quiz->title }}
        </h2>
        <p class="text-sm text-gray-500 mt-1">
            By: 
            <a href="{{ route('profile.show', $quiz->user->id) }}" class="hover:underline">
                {{ $quiz->user->name }}
            </a>
        </p>
        @if ($quiz->user_id != auth()->id())
            <!-- Favorite Button Under Author -->
            <div 
                x-data="favoriteComponent({{ $quiz->id }}, '{{ $quiz->favorites->contains('user_id', auth()->id()) ? 'true' : 'false' }}', '{{ $quiz->favorites->count() }}', 'quiz')" 
                class="mt-2"
            >
                <form @submit.prevent="toggle">
                    <button type="submit" x-text="favorited ? '❤️' : '🤍'" class="text-xl"></button>
                </form>

            </div>
        @endif
    </x-slot>

    <div class="max-w-4xl mx-auto mt-6 p-6 bg-white shadow rounded space-y-6">
        @php
            $correctCount = collect($results)->where('is_correct', true)->count();
        @endphp

        <div class="p-4 bg-blue-100 rounded text-blue-800">
            You got <strong>{{ $correctCount }}</strong> out of <strong>{{ count($results) }}</strong> questions correct.
        </div>

        @foreach ($results as $index => $result)
            <div class="p-4 rounded shadow-sm {{ $result['is_correct'] ? 'bg-green-100' : 'bg-red-100' }}">
                <h3 class="font-semibold mb-2">Question {{ $index + 1 }}: {{ $result['question'] }}</h3>

                <p class="mb-1">
                    Your Answer:
                    <span class="font-medium text-gray-800">
                        {{ $result['selected_text'] ?? 'Not answered' }}
                    </span>
                </p>

                <p>
                    Status:
                    @if ($result['is_correct'])
                        <span class="text-green-700 font-semibold">✔ Correct</span>
                    @else
                        <span class="text-red-700 font-semibold">✘ Incorrect</span>
                        <br>
                        <span class="text-sm text-gray-700">
                            Correct Answer: {{ $result['correct_text'] ?? 'N/A' }}
                        </span>
                    @endif
                </p>
            </div>
        @endforeach

        <div class="text-right">
            <form method="GET" action="{{ route('quiz.show', $quiz) }}">
                <x-primary-button>Retake Quiz</x-primary-button>
            </form>
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