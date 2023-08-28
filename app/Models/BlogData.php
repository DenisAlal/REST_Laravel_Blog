<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogData extends Model
{
    use HasFactory;

    protected $table = 'blog_data';

    protected $fillable = [
        'blog_data_name',
        'blog_data',
        'video_id',
        'image_id',
        'create_date',
        'blog_type_id',
    ];

    public function blog_type() {
        return $this->belongsTo(BlogType::class, 'blog_type_id');
    }

    public function likeCounter()
    {
        return $this->hasMany(LikeCounter::class);
    }
    public function comments()
    {
        return $this->hasMany(Comments::class);
    }

}
