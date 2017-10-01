<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ZcCategory
 */
class ZcCategory extends Model
{
    protected $table = 'zc_category';

    protected $primaryKey = 'cat_id';

    public $timestamps = false;

    protected $fillable = [
        'cat_name',
        'keywords',
        'measure_unit',
        'show_in_nav',
        'style',
        'grade',
        'filter_attr',
        'is_top_style',
        'top_style_tpl',
        'cat_icon',
        'is_top_show',
        'category_links',
        'category_topic',
        'pinyin_keyword',
        'cat_alias_name',
        'template_file',
        'cat_desc',
        'parent_id',
        'sort_order',
        'is_show'
    ];

    protected $guarded = [];
}
