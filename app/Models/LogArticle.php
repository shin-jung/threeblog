<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogArticle extends Model
{
    protected $fillable = [
        'article_id', 'is_admin', 'ip', 'type', 'previous_message',
        'current_message'
    ];
}
