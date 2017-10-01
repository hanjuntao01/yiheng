<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ArticleExtend
 */
class ArticleExtend extends Model
{
    protected $table = 'article_extend';

    public $timestamps = false;

    protected $fillable = [
        'article_id',
        'click',
        'likenum',
        'hatenum'
    ];

    protected $guarded = [];
}
