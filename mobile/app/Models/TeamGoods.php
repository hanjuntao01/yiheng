<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TeamGoods
 */
class TeamGoods extends Model
{
    protected $table = 'team_goods';

    public $timestamps = false;

    protected $fillable = [
        'goods_id',
        'team_price',
        'team_num',
        'validity_time',
        'limit_num',
        'astrict_num',
        'tc_id',
        'is_audit',
        'is_team',
        'sort_order'
    ];

    protected $guarded = [];
}
