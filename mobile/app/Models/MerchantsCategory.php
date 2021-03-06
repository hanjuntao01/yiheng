<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MerchantsCategory
 */
class MerchantsCategory extends Model
{
    protected $table = 'merchants_category';

    protected $primaryKey = 'cat_id';

    public $timestamps = false;

    protected $fillable = [
        'cat_name',
        'parent_id',
        'is_show',
        'user_id',
        'keywords',
        'cat_desc',
        'sort_order',
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
        'add_titme',
        'touch_icon'
    ];

    protected $guarded = [];
}
