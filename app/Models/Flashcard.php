<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Flashcard extends Model
{
    use HasFactory;

    protected $fillable = ['question', 'answer', 'flashcard_set_id'];

    public function flashcardSet() {
        return $this->belongsTo(FlashcardSet::class);
    }
}
