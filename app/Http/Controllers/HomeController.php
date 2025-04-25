<?php

namespace App\Http\Controllers;

use App\Models\FlashcardSet;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = request()->user();
    
        $quizzes = Quiz::where('user_id', $user->id)->latest()->get();
        $flashcardSets = FlashcardSet::where('user_id', $user->id)->latest()->get();
    
        $favoriteQuizzes = Quiz::whereHas('favorites', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('user')->get();

        $favoriteFlashcardSets = FlashcardSet::whereHas('favorites', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('user')->get();

        $recentViews = $user->recentViews()->with('viewable')->orderByDesc('updated_at')->take(6)->get();
    
        return view('home.index', compact(
            'quizzes',
            'flashcardSets',
            'favoriteQuizzes',
            'favoriteFlashcardSets',
            'recentViews',
        ));
    }
}
