<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = ['answer', 'question_id', 'is_correct'];
    public function question() {
        return $this->belongsTo(Question::class);
    }
}
