<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PresaleActivity
 */
class PresaleActivity extends Model
{
    protected $table = 'presale_activity';

    protected $primaryKey = 'act_id';

    public $timestamps = false;

    protected $fillable = [
        'act_name',
        'cat_id',
        'user_id',
        'goods_id',
        'goods_name',
        'act_desc',
        'deposit',
        'start_time',
        'end_time',
        'pay_start_time',
        'pay_end_time',
        'is_finished',
        'review_status',
        'review_content',
        'pre_num'
    ];

    protected $guarded = [];
}
