<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Wholesale
 */
class Wholesale extends Model
{
    protected $table = 'wholesale';

    protected $primaryKey = 'act_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'goods_id',
        'goods_name',
        'rank_ids',
        'prices',
        'enabled',
        'review_status',
        'review_content'
    ];

    protected $guarded = [];
}
