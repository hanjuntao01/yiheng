<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PresaleCat
 */
class PresaleCat extends Model
{
    protected $table = 'presale_cat';

    protected $primaryKey = 'cat_id';

    public $timestamps = false;

    protected $fillable = [
        'cat_name',
        'keywords',
        'cat_desc',
        'measure_unit',
        'show_in_nav',
        'style',
        'is_show',
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
        'parent_id',
        'sort_order'
    ];

    protected $guarded = [];
}
