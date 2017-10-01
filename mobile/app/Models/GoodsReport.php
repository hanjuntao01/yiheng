<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GoodsReport
 */
class GoodsReport extends Model
{
    protected $table = 'goods_report';

    protected $primaryKey = 'report_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'user_name',
        'goods_id',
        'goods_name',
        'goods_image',
        'title_id',
        'type_id',
        'inform_content',
        'add_time',
        'report_state',
        'handle_type',
        'handle_message',
        'handle_time',
        'admin_id'
    ];

    protected $guarded = [];
}
