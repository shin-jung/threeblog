<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleMessage extends Model
{
    protected $fillable = [
        'article_id', 'user_id', 'count_like', 'content', 'file'
    ];
}
