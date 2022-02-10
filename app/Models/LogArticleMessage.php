<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogArticleMessage extends Model
{
    protected $fillable = [
        'article_message_id', 'is_admin', 'ip', 'type', 'previous_message',
        'current_message'
    ];
}
