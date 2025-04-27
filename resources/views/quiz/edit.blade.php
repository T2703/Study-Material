<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            Edit Quiz
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto mt-6 p-4 bg-white shadow-md rounded">
        <form action="{{ route('quiz.update', $quiz) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Quiz Title --}}
            <div class="mb-4">
                <label for="title" class="block font-medium text-sm text-gray-700">Quiz Title</label>
                <input type="text" name="title" id="title" required
                       class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                       value="{{ old('title', $quiz->title ?? '') }}">
                @error('title')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Quiz Description --}}
            <div class="mb-6">
                <label for="description" class="block font-medium text-sm text-gray-700">Description</label>
                <textarea name="description" id="description" rows="3"
                          class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                          placeholder="Brief description...">{{ old('description', $quiz->description ?? '') }}</textarea>
            </div>

            {{-- Dynamic Questions --}}
            <div id="questionsContainer" class="space-y-6"></div>

            <div class="flex items-center justify-between mt-6">
                <button type="button" onclick="addQuestion()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded">
                    ➕ Add Question
                </button>
    
                <x-primary-button>Update Quiz</x-primary-button>
            </div>
        </form>

        <div class="flex items-center gap-4">
            
            <form action="{{ route('quiz.destroy', $quiz) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Are you sure you want to delete this quiz?')" class="bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2 rounded transition duration-200">
                    🗑️ Delete
                </button>
            </form>
        </div>

        @if(isset($quiz))
            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    @foreach ($quiz->questions as $qIndex => $question)
                        addQuestionWithData(@json($question), {{ $qIndex }});
                    @endforeach
                });

                function addQuestionWithData(question, qIndex) {
                    const container = document.getElementById('questionsContainer');
                    const questionBlock = document.createElement('div');
                    questionBlock.classList.add('p-4', 'bg-gray-100', 'rounded', 'shadow-sm');
                    questionBlock.id = `question-block-${qIndex}`;

                    let answerInputs = '';
                    question.answers.forEach((answer, aIndex) => {
                        const isCorrect = answer.is_correct ? 'checked' : '';
                        answerInputs += `
                            <div class="flex items-center gap-2">
                                <input type="radio" name="questions[${qIndex}][correct]" value="${aIndex}" ${isCorrect} required>
                                <input type="text" name="questions[${qIndex}][answers][]" class="w-full rounded border-gray-300" value="${answer.answer}" required>
                            </div>
                        `;
                    });

                    questionBlock.innerHTML = `
                        <label class="block font-semibold">Question</label>
                        <input type="text" name="questions[${qIndex}][question]" class="w-full mb-2 rounded border-gray-300" required value="${question.question}">

                        <div class="answers-container space-y-2" id="answers-${qIndex}">
                            <label class="block font-semibold mt-2">Answers</label>
                            ${answerInputs}
                        </div>

                        <div class="flex gap-4 mt-2">
                            <button type="button" onclick="addAnswer(${qIndex})" class="text-sm text-blue-600 hover:underline">+ Add Answer</button>
                            <button type="button" onclick="removeAnswer(${qIndex})" class="text-sm text-red-600 hover:underline">− Remove Last Answer</button>
                            <button type="button" onclick="removeQuestion(${qIndex})" class="text-sm text-gray-600 hover:underline ml-auto">🗑️ Remove Question</button>
                        </div>
                    `;

                    container.appendChild(questionBlock);
                    questionIndex = qIndex + 1; // keep index in sync
                }
            </script>
        @endif
    </div>
</x-app-layout>

<script>
    let questionIndex = 0;

    function addQuestion() {
        const container = document.getElementById('questionsContainer');
        const qIndex = questionIndex++;

        const questionBlock = document.createElement('div');
        questionBlock.classList.add('p-4', 'bg-gray-100', 'rounded', 'shadow-sm');
        questionBlock.id = `question-block-${qIndex}`;

        questionBlock.innerHTML = `
            <label class="block font-semibold">Question</label>
            <input type="text" name="questions[${qIndex}][question]" class="w-full mb-2 rounded border-gray-300" required>

            <div class="answers-container space-y-2" id="answers-${qIndex}">
                <label class="block font-semibold mt-2">Answers</label>
                ${generateAnswerInput(qIndex, 0, true)}  
                ${generateAnswerInput(qIndex, 1, false)} 
                ${generateAnswerInput(qIndex, 2, false)} 
                ${generateAnswerInput(qIndex, 3, false)} 
            </div>

            <div class="flex gap-4 mt-2">
                <button type="button" onclick="addAnswer(${qIndex})" class="text-sm text-blue-600 hover:underline">+ Add Answer</button>
                <button type="button" onclick="removeAnswer(${qIndex})" class="text-sm text-red-600 hover:underline">− Remove Last Answer</button>
                <button type="button" onclick="removeQuestion(${qIndex})" class="text-sm text-gray-600 hover:underline ml-auto">🗑️ Remove Question</button>
            </div>
        `;

        container.appendChild(questionBlock);
    }


    function removeQuestion(qIndex) {
        const questionBlock = document.getElementById(`question-block-${qIndex}`);
        if (questionBlock) {
            questionBlock.remove();
        }
    }

    function generateAnswerInput(qIndex, aIndex, isCorrect = false) {
        return `
            <div class="flex items-center gap-2">
                <input type="radio" name="questions[${qIndex}][correct]" value="${aIndex}" ${isCorrect ? 'checked' : ''} required>
                <input type="text" name="questions[${qIndex}][answers][]" class="w-full rounded border-gray-300" placeholder="Answer ${aIndex + 1}" required>
            </div>
        `;
    }

    function addAnswer(qIndex) {
        const answerContainer = document.getElementById(`answers-${qIndex}`);
        const div = document.createElement('div');
        div.classList.add('flex', 'items-center', 'gap-2');

        div.innerHTML = `
            <input type="radio" name="questions[${qIndex}][correct]" required>
            <input type="text" name="questions[${qIndex}][answers][]" class="w-full rounded border-gray-300" placeholder="Answer" required>
        `;

        answerContainer.appendChild(div);
        syncAnswerIndexes(qIndex);
    }

    function removeAnswer(qIndex) {
        const answerContainer = document.getElementById(`answers-${qIndex}`);
        const inputs = answerContainer.querySelectorAll('.flex.items-center');

        if (inputs.length > 2) {
            inputs[inputs.length - 1].remove();
            syncAnswerIndexes(qIndex);
        } else {
            alert("At least two answers are required.");
        }
    }

    function syncAnswerIndexes(qIndex) {
        const answerContainer = document.getElementById(`answers-${qIndex}`);
        const rows = answerContainer.querySelectorAll('.flex.items-center');

        rows.forEach((row, index) => {
            const radio = row.querySelector('input[type="radio"]');
            const text = row.querySelector('input[type="text"]');
            radio.value = index;
            text.placeholder = `Answer ${index + 1}`;
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form[action="{{ route('quiz.update', $quiz) }}"]');

        form.addEventListener('submit', function (e) {
            const questionsContainer = document.getElementById('questionsContainer');
            if (!questionsContainer || questionsContainer.children.length === 0) {
                e.preventDefault(); // Stop form submission
                alert('Please add at least one question before updating the quiz.');
            }
        });
    });
</script>
