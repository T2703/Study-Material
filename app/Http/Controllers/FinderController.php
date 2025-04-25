<?php

namespace App\Http\Controllers;

use App\Models\FlashcardSet;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Http\Request;

class FinderController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = request()->user();
    
        $quizzes = Quiz::with('user', 'favorites')
            ->where('user_id', '!=', $user->id)
            ->latest()
            ->get();
    
        $flashcardSets = FlashcardSet::with('user', 'favorites')
            ->where('user_id', '!=', $user->id)
            ->latest()
            ->get();
    
        return view('finder.index', compact(
            'quizzes',
            'flashcardSets',
        ));
    }

    public function search(Request $request)
    {
        $user = request()->user();

        $search = $request->input('search');

        $quizzes = Quiz::with('user', 'favorites')
            ->where('title', 'like', "%{$search}%")
            ->where('user_id', '!=', $user->id)
            ->get()
            ->map(function ($quiz) {
                $quiz->type = 'quiz';
                return $quiz;
            });

        $flashcardSets = FlashcardSet::with('user', 'favorites')
            ->where('title', 'like', "%{$search}%")
            ->where('user_id', '!=', $user->id)
            ->get()
            ->map(function ($flashcardSet) {
                $flashcardSet->type = 'flashcard';
                return $flashcardSet;
            });


        return view('finder.index', compact('quizzes', 'flashcardSets', 'search'));

    }
}
