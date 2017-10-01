<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ZcTopic
 */
class ZcTopic extends Model
{
    protected $table = 'zc_topic';

    protected $primaryKey = 'topic_id';

    public $timestamps = false;

    protected $fillable = [
        'parent_topic_id',
        'reply_topic_id',
        'topic_status',
        'topic_content',
        'user_id',
        'pid',
        'add_time'
    ];

    protected $guarded = [];
}
