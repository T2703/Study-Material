<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\RecentView;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $quizzes = Quiz::query()
        ->where('user_id', request()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('quiz.index', [compact('quizzes')]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   
        return view('quiz.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $data = $request->validate([
            'title' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.question' => ['required', 'string'],
            'questions.*.correct' => ['required', 'integer'],
            'questions.*.answers' => ['array'],
            'questions.*.answers.*' => ['required', 'string'],
        ]);

       $data['user_id'] = $request->user()->id;
       $quiz = Quiz::create($data);
    
       foreach ($data['questions'] as $q) {
            logger()->info("Correct index for question '{$q['question']}': {$q['correct']}");
            $question = $quiz->questions()->create([
                'question' => $q['question'],
            ]);

            foreach ($q['answers'] as $aIndex => $answerText) {
                $isCorrect = (int) $aIndex === (int) $q['correct'];
                logger()->info("Answer Index: {$aIndex}, Correct Answer Index: {$q['correct']}, is_correct: {$isCorrect}");
                $question->answers()->create([
                    'answer' => $answerText,
                    'is_correct' => $isCorrect,
                ]);
            }
        }

        $createdAnswers = $question->answers()->get();
        logger()->info("Question: {$question->question}", $createdAnswers->toArray());

       return to_route('quiz.take', $quiz);
    }

    /**
     * Display the specified resource.
     */
    public function show(Quiz $quiz)
    {
        //$user = auth()->user();

        $quiz->load('questions.answers');

        return view('quiz.show', compact('quiz'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quiz $quiz)
    {
        $quiz->load('questions.answers');

        if ($quiz->user_id !== request()->user()->id) {
            abort(403);
        }
        return view('quiz.edit', compact('quiz'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quiz $quiz)
    {
        $data = $request->validate([
            'title' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.id' => ['nullable', 'integer'],
            'questions.*.question' => ['required', 'string'],
            'questions.*.correct' => ['required', 'integer'],
            'questions.*.answers' => ['array'],
            'questions.*.answers.*' => ['required', 'string'],
        ]);

        $quiz->update([
            'title' => $data['title'],
            'description' => $data['description'],
        ]);

        $quiz->questions()->delete();
    
       foreach ($data['questions'] as $q) {
            $question = $quiz->questions()->create([
                'question' => $q['question'],
            ]);

            foreach ($q['answers'] as $aIndex => $answerText) {
                $isCorrect = (int) $aIndex === (int) $q['correct'];

                $question->answers()->create([
                    'answer' => $answerText,
                    'is_correct' => $isCorrect,
                ]);
            }
        }
        return to_route('quiz.show', $quiz)->with('message', 'Quiz updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz)
    {
        if ($quiz->user_id !== request()->user()->id) {
            abort(403);
        }
    
        $quiz->delete();
    
        // Get the referer URL
        $referer = url()->previous();
    
        // Redirect logic
        if (str_contains($referer, '/profile/')) {
            return redirect()->route('profile.show', $quiz->user_id)->with('message', 'Quiz deleted from profile.');
        }
    
        return redirect()->route('home.index')->with('message', 'Quiz deleted successfully!');
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $quiz->load('questions.answers');
        $answers = $request->input('answers'); 

        $results = [];

        foreach ($quiz->questions as $question) {
            $selectedAnswerId = $answers[$question->id] ?? null;
        
            $selectedAnswer = $question->answers->firstWhere('id', $selectedAnswerId);
            $correctAnswer = $question->answers->first(fn($a) => $a->is_correct);
        
            $results[] = [
                'question' => $question->question,
                'selected' => $selectedAnswerId,
                'selected_text' => $selectedAnswer?->answer,
                'correct_text' => $correctAnswer?->answer,
                'is_correct' => $selectedAnswer?->is_correct ?? false,
            ];
        }
        


        return view('quiz.result', [
            'quiz' => $quiz,
            'results' => $results,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function take(Quiz $quiz)
    {
        //$user = auth()->user();
        $quiz->load('questions.answers');

        RecentView::updateOrCreate([
            'user_id' => auth()->id(),
            'viewable_id' => $quiz->id, 
            'viewable_type' => Quiz::class, 
        ],
        [
            'updated_at' => now(), 
        ]);

        return view('quiz.take', compact('quiz'));
    }
}
