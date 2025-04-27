<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\FlashcardSet;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Profiler\Profile;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user(); // get the authenticated user
    
        // Update the fields validated by the ProfileUpdateRequest
        $user->fill($request->validated());
    
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
    
        // Handle the profile picture
        if ($request->hasFile('profile_picture')) {
            $request->validate([
                'profile_picture' => ['nullable', 'image', 'max:2048'], // only validate if file present
            ]);
    
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
    
            // Delete old picture if exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
    
            $user->profile_picture = $path;
        }
    
        $user->save();
    
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $profile)
    {
        $quizzes = Quiz::where('user_id', $profile->id)
        ->orderBy('created_at', 'desc')
        ->get();

        $flashcardSets = FlashcardSet::where('user_id', $profile->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('profile.show', compact('profile', 'quizzes', 'flashcardSets'));
    }

    public function search(Request $request, User $profile)
    {
        $search = $request->input('search');

        $quizzes = Quiz::with('user', 'favorites')
            ->where('title', 'like', "%{$search}%")
            ->where('user_id', $profile->id)
            ->get()
            ->map(function ($quiz) {
                $quiz->type = 'quiz';
                return $quiz;
            });
        
        $flashcardSets = FlashcardSet::with('user', 'favorites')
            ->where('title', 'like', "%{$search}%")
            ->where('user_id', $profile->id)
            ->get()
            ->map(function ($flashcardSet) {
                $flashcardSet->type = 'flashcard';
                return $flashcardSet;
            });
        
        return view('profile.show', [
            'profile' => $profile,
            'quizzes' => $quizzes,
            'flashcardSets' => $flashcardSets,
            'search' => $search
        ]);

    }
}
