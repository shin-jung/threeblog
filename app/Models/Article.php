<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'author', 'title', 'content', 'count_like',
    ];

    public function relatedAuthor()
    {
        return $this->hasOne('App\Models\User', 'id', 'author');
    }
}
