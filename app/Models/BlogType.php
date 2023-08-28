<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogType extends Model
{
    use HasFactory;
    protected $table = 'blog_type';

    protected $fillable = [
        'blog_type_name',
        'description',
    ];

    public function blog_data() {
        return $this->hasMany(BlogData::class);
    }

}
