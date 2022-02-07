<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LikeToArticle extends Model
{
    protected $fillable = [
        'article_id', 'user_id',
    ];
}
