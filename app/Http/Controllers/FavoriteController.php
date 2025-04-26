<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\FlashcardSet;
use App\Models\User;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index()
    {
        $user = request()->user();

        $favoriteQuizzes = Quiz::whereHas('favorites', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('user')->get();

        $favoriteFlashcardSets = FlashcardSet::whereHas('favorites', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('user')->get();
    
        return view('favorite.index', [
            'profile' => $user,
            'favoriteQuizzes' => $favoriteQuizzes,
            'favoriteFlashcardSets' => $favoriteFlashcardSets,
        ]);
    }

    public function toggleFavorite(Request $request, $type, $id)
    {
        $user = $request->user();

        $model = match ($type) {
            'quiz' => Quiz::findOrFail($id),
            'flashcard' => FlashcardSet::findOrFail($id),
        };

        if ($model->user_id === $user->id) {
            return back()->with('message', "You can't favorite your own $type.");
        }

        $alreadyFavorited = $model->favorites()->where('user_id', $user->id)->first();
        
        if ($alreadyFavorited) {
            $alreadyFavorited->delete();
        } else {
            $model->favorites()->create(['user_id' => $user->id]);
        }

        return back()->with('message', 'Favorite toggled!');
    }

    public function search(Request $request, User $profile)
    {
        $search = $request->input('search');
    
        $favoriteQuizzes = Quiz::with('user', 'favorites')
            ->whereHas('favorites', function ($query) use ($profile) {
                $query->where('user_id', $profile->id);
            })
            ->where('title', 'like', "%{$search}%")
            ->get()
            ->map(function ($quiz) {
                $quiz->type = 'quiz';
                return $quiz;
            });
    
        $favoriteFlashcardSets = FlashcardSet::with('user', 'favorites')
            ->whereHas('favorites', function ($query) use ($profile) {
                $query->where('user_id', $profile->id);
            })
            ->where('title', 'like', "%{$search}%")
            ->get()
            ->map(function ($flashcardSet) {
                $flashcardSet->type = 'flashcard';
                return $flashcardSet;
            });
    
        return view('favorite.index', [
            'profile' => $profile,
            'favoriteQuizzes' => $favoriteQuizzes,
            'favoriteFlashcardSets' => $favoriteFlashcardSets,
            'search' => $search
        ]);
    }

}
