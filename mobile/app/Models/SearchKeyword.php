<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SearchKeyword
 */
class SearchKeyword extends Model
{
    protected $table = 'search_keyword';

    protected $primaryKey = 'keyword_id';

    public $timestamps = false;

    protected $fillable = [
        'keyword',
        'pinyin',
        'is_on',
        'count',
        'addtime',
        'pinyin_keyword'
    ];

    protected $guarded = [];
}
