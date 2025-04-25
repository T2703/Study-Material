<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\FlashcardSet;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
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

}
