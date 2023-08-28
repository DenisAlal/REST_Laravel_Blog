<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LikeCounter extends Model
{
    use HasFactory;

    protected $table = 'like_counter';

    protected $fillable = [
        'user_id',
        'blog_data_id',
        'like',
        'dislike',
    ];


}
