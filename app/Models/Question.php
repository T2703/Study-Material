<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['question', 'quiz_id', 'answer_id'];

    public function quiz() {
        return $this->belongsTo(Quiz::class);
    }

    public function answers() {
        return $this->hasMany(Answer::class);
    }
}
