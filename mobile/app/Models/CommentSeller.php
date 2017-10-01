<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CommentSeller
 */
class CommentSeller extends Model
{
    protected $table = 'comment_seller';

    protected $primaryKey = 'sid';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'ru_id',
        'order_id',
        'desc_rank',
        'service_rank',
        'delivery_rank',
        'sender_rank',
        'add_time'
    ];

    protected $guarded = [];
}
