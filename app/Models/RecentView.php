<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecentView extends Model
{
    public $timestamps = true;
    
    protected $fillable = ['user_id', 'viewable_id', 'viewable_type', 'updated_at'];

    public function viewable()
    {
        return $this->morphTo();
    }
    
}
