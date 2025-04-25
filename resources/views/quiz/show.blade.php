<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            {{ $quiz->title }}
            <br>
            <a href="{{ route('profile.show', $quiz->user->id) }}" class="text-sm text-gray-500">By: {{ $quiz->user->name }}</a>
        </h2>
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

            <x-primary-button>Submit Answers</x-primary-button>
        </form>
    </div>
</x-app-layout>