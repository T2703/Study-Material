<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold leading-tight text-gray-800">
            {{ $quiz->title }}
        </h2>
        <p class="text-sm text-gray-500 mt-1">
            By: <a href="{{ route('profile.show', $quiz->user->id) }}" class="hover:underline">{{ $quiz->user->name }}</a>
        </p>
    </x-slot>

    <div class="max-w-3xl mx-auto mt-6 bg-white shadow rounded-lg p-6">
        <p class="text-lg text-gray-700 mb-4">
            {{ $quiz->description ?? 'No description provided for this quiz.' }}
        </p>

        <div class="flex items-center justify-between mb-6">
            <span class="text-sm text-gray-600">
                Total Questions: <strong>{{ $quiz->questions->count() }}</strong>
            </span>

            <span class="text-sm text-gray-600">
                Created on: <strong>{{ $quiz->created_at->format('M d, Y') }}</strong>
            </span>
        </div>

        <div class="text-right">
            <form method="GET" action="{{ route('quiz.show', $quiz) }}">
                <x-primary-button>Start Quiz</x-primary-button>
            </form>
        </div>
    </div>
</x-app-layout>
