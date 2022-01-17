<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LikeToArticleMessage extends Model
{
    protected $fillable = [
        'article_message_id', 'user_id',
    ];
}
