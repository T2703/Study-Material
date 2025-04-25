<?php

namespace App\Http\Controllers;

use App\Models\FlashcardSet;
use App\Models\RecentView;
use Illuminate\Http\Request;

class FlashcardSetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('flashcardSet.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'flashcards' => ['required', 'array', 'min:1'],
            'flashcards.*.question' => ['required', 'string'],
            'flashcards.*.answer' => ['required', 'string'],
        ]);

        $data['user_id'] = $request->user()->id;
        $flashcardSet = FlashcardSet::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'user_id' => $data['user_id'],
        ]);
    
        foreach ($data['flashcards'] as $card) {
            $flashcardSet->flashcards()->create([
                'question' => $card['question'],
                'answer' => $card['answer'],
            ]);
        }

        //dd($data['flashcards']);
        return to_route('flashcardSet.show', $flashcardSet);
    }

    /**
     * Display the specified resource.
     */
    public function show(FlashcardSet $flashcardSet)
    {
        $flashcardSet->load('flashcards');

        RecentView::updateOrCreate([
            'user_id' => auth()->id(),
            'viewable_id' => $flashcardSet->id, // or flashcardSet->id
            'viewable_type' => get_class($flashcardSet), // or get_class($flashcardSet)
        ]);

        return view('flashcardSet.show', compact('flashcardSet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FlashcardSet $flashcardSet)
    {
        if ($flashcardSet->user_id !== request()->user()->id) {
            abort(403);
        }
        return view('flashcardSet.edit', compact('flashcardSet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FlashcardSet $flashcardSet)
    {
        $data = $request->validate([
            'title' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'flashcards' => ['required', 'array', 'min:1'],
            'flashcards.*.question' => ['required', 'string'],
            'flashcards.*.answer' => ['required', 'string'],
        ]);

        $flashcardSet->update([
            'title' => $data['title'],
            'description' => $data['description'],
        ]);

        $flashcardSet->flashcards()->delete();
    
        foreach ($data['flashcards'] as $card) {
            $flashcardSet->flashcards()->create([
                'question' => $card['question'],
                'answer' => $card['answer'],
            ]);
        }

        return redirect()->route('flashcardSet.show', $flashcardSet)->with('message', 'Flashcard set updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FlashcardSet $flashcardSet)
    {
        if ($flashcardSet->user_id !== request()->user()->id) {
            abort(403);
        }
        
        $flashcardSet->delete();

        // Get the referer URL
        $referer = url()->previous();

        // Redirect logic
        if (str_contains($referer, '/profile/')) {
            return redirect()->route('profile.show', $flashcardSet->user_id)->with('message', 'Flashcard Set deleted from profile.');
        }

        return to_route('home.index')->with('message', 'Note was deleted');
    }

    /**
     * This favorites
     * @param \Illuminate\Http\Request $request
     * @param mixed $type
     * @param mixed $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleFavorite(Request $request, $type, $id)
    {
        $user = $request->user();

        $model = match ($type) {
            'flashcard' => FlashcardSet::findOrFail($id),
        };

        $alreadyFavorited = $model->favorites()->where('user_id', $user->id)->first();

        if ($alreadyFavorited) {
            $alreadyFavorited->delete();
        } else {
            $model->favorites()->create(['user_id' => $user->id]);
        }

        return back()->with('message', 'Favorite toggled!');
    }

}
