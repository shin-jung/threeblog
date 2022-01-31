<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleMessage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'article_id', 'user_id', 'count_like', 'content', 'file', 'parent'
    ];

    public function relatedArticle()
    {
        return $this->hasOne('App\Models\Article', 'id', 'article_id');
    }
}
