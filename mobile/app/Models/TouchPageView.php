<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TouchPageView
 */
class TouchPageView extends Model
{
    protected $table = 'touch_page_view';

    public $timestamps = true;

    protected $fillable = [
        'ru_id',
        'type',
        'page_id',
        'title',
        'keywords',
        'description',
        'data',
        'pic',
        'thumb_pic',
        'create_at',
        'update_at',
        'default',
        'review_status',
        'is_show'
    ];

    protected $guarded = [];
}
