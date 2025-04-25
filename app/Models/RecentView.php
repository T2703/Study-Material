<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecentView extends Model
{
    protected $fillable = ['user_id', 'viewable_id', 'viewable_type'];

    public function viewable()
    {
        return $this->morphTo();
    }
    
}
