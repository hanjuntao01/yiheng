<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ArticleCat
 */
class ArticleCat extends Model
{
    protected $table = 'article_cat';

    protected $primaryKey = 'cat_id';

    public $timestamps = false;

    protected $hidden = ['cat_id', 'cat_type'];

    protected $visible = [];

    protected $appends = ['id', 'url'];

    protected $fillable = [
        'cat_name',
        'cat_type',
        'keywords',
        'cat_desc',
        'sort_order',
        'show_in_nav',
        'parent_id'
    ];

    protected $guarded = [];

    /**
     * 获取分类文章
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function article()
    {
        return $this->belongsTo('App\Models\Article', 'cat_id', 'cat_id');
    }

    public function getIdAttribute()
    {
        return $this->attributes['cat_id'];
    }

    public function getUrlAttribute()
    {
        return url('article/index/category', array('id' => $this->attributes['cat_id']));
    }
}
