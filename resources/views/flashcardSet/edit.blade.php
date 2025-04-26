<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            Edit Flashcard Set
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto mt-6 p-4 bg-white shadow-md rounded">
        <form action="{{ route('flashcardSet.update', $flashcardSet) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Flashcard Title --}}
            <div class="mb-4">
                <label for="title" class="block font-medium text-sm text-gray-700">Flashcard Set Title</label>
                <input type="text" name="title" id="title" required
                       class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                       value="{{ old('title', $flashcardSet->title ?? '') }}">
                @error('title')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Flashcard Description --}}
            <div class="mb-6">
                <label for="description" class="block font-medium text-sm text-gray-700">Description</label>
                <textarea name="description" id="description" rows="3"
                          class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                          placeholder="Brief description...">{{ old('description', $flashcardSet->description ?? '') }}</textarea>
            </div>

            {{-- Dynamic Flashcards --}}
            <div id="flashcardsContainer" class="space-y-6">
                @foreach ($flashcardSet->flashcards as $index => $flashcard)
                    <div class="p-4 bg-gray-100 rounded shadow-sm" id="flashcard-block-{{ $index }}">
                        <div class="mb-4">
                            <label class="block font-semibold">Question</label>
                            <input type="text" name="flashcards[{{ $index }}][question]" class="w-full rounded border-gray-300" required value="{{ $flashcard->question }}">
                        </div>
                        <div class="mb-4">
                            <label class="block font-semibold">Answer</label>
                            <input type="text" name="flashcards[{{ $index }}][answer]" class="w-full rounded border-gray-300" required value="{{ $flashcard->answer }}">
                        </div>
                        <div class="text-right">
                            <button type="button" onclick="removeFlashcard({{ $index }})" class="text-sm text-red-600 hover:underline">🗑️ Remove Flashcard</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex items-center justify-between mt-6">
                <button type="button" onclick="addFlashcard()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded">
                    ➕ Add Flashcard
                </button>

                <x-primary-button>Update Flashcard Set</x-primary-button> 
            </div>
        </form>

        <div class="flex items-center gap-4">    
            <form action="{{ route('flashcardSet.destroy', $flashcardSet) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Are you sure you want to delete this quiz?')" class="bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2 rounded transition duration-200">
                    🗑️ Delete
                </button>
            </form>
        </div>
    </div>
</x-app-layout>

<script>
    let flashcardIndex = {{ $flashcardSet->flashcards->count() }};

    function addFlashcard() {
        const container = document.getElementById('flashcardsContainer');
        const fcIndex = flashcardIndex++;

        const block = document.createElement('div');
        block.classList.add('p-4', 'bg-gray-100', 'rounded', 'shadow-sm');
        block.id = `flashcard-block-${fcIndex}`;

        block.innerHTML = `
            <div class="mb-4">
                <label class="block font-semibold">Question</label>
                <input type="text" name="flashcards[${fcIndex}][question]" class="w-full rounded border-gray-300" required>
            </div>
            <div class="mb-4">
                <label class="block font-semibold">Answer</label>
                <input type="text" name="flashcards[${fcIndex}][answer]" class="w-full rounded border-gray-300" required>
            </div>
            <div class="text-right">
                <button type="button" onclick="removeFlashcard(${fcIndex})" class="text-sm text-red-600 hover:underline">🗑️ Remove Flashcard</button>
            </div>
        `;

        container.appendChild(block);
    }

    function removeFlashcard(fcIndex) {
        const block = document.getElementById(`flashcard-block-${fcIndex}`);
        if (block) block.remove();
    }
</script>