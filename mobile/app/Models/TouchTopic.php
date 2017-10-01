<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TouchTopic
 */
class TouchTopic extends Model
{
    protected $table = 'touch_topic';

    public $timestamps = false;

    protected $fillable = [
        'topic_id',
        'user_id',
        'title',
        'intro',
        'start_time',
        'end_time',
        'data',
        'template',
        'css',
        'topic_img',
        'title_pic',
        'base_style',
        'htmls',
        'keywords',
        'description',
        'review_status',
        'review_content'
    ];

    protected $guarded = [];
}
