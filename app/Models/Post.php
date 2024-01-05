<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'name', 'cover_image', 'images', 'tags', 'start_date', 'end_date', 'all_dates'
    ];
    public function likes()
    {
        return $this->belongsToMany(User::class, 'post_user','post_id','user_id')
            ->withTimestamps();
    }
}
