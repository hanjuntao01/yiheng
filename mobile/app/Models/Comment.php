<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Comment
 */
class Comment extends Model
{
    protected $table = 'comment';

    protected $primaryKey = 'comment_id';

    public $timestamps = false;

    protected $fillable = [
        'comment_type',
        'id_value',
        'email',
        'user_name',
        'content',
        'comment_rank',
        'comment_server',
        'comment_delivery',
        'add_time',
        'ip_address',
        'status',
        'parent_id',
        'user_id',
        'ru_id',
        'single_id',
        'order_id',
        'rec_id',
        'goods_tag',
        'useful',
        'useful_user',
        'use_ip',
        'dis_id',
        'like_num',
        'dis_browse_num'
    ];

    protected $guarded = [];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'user_id', 'user_id');
    }
}
