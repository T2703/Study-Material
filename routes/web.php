<?php

use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FinderController;
use App\Http\Controllers\FlashcardSetController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

// Dashboard Routes.
Route::redirect('/', '/home')->name('dashboard');

Route::get('/finder/search', [FinderController::class, 'search'])->name('finder.search');

Route::middleware(['auth', 'verified'])->group(function() {
    // Home Routes
    Route::resource('home', HomeController::class);

    // Finder Routes
    Route::resource('finder', FinderController::class);

    // Quiz Routes
    Route::resource('quiz', QuizController::class);
    Route::post('/quiz/{quiz}/submit', [QuizController::class, 'submit'])->name('quiz.submit');
    Route::get('/quiz/take/{quiz}', [QuizController::class, 'take'])->name('quiz.take');
    Route::post('/quiz/start/{quiz}', [QuizController::class, 'start'])->name('quiz.start');


    // Flashcard Routes
    Route::resource('flashcardSet', FlashcardSetController::class);

    // Favorite Routes
    Route::post('/favorite/{type}/{id}', [FavoriteController::class, 'toggleFavorite'])->name('favorite.toggle');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/{profile}', [ProfileController::class, 'show'])->name('profile.show');    
    Route::get('/profile/{profile}/search', [ProfileController::class, 'search'])->name('profile.search');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
