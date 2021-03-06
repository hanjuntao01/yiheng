<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BonusType
 */
class BonusType extends Model
{
    protected $table = 'bonus_type';

    protected $primaryKey = 'type_id';

    public $timestamps = false;

    protected $fillable = [
        'type_name',
        'user_id',
        'type_money',
        'send_type',
        'usebonus_type',
        'min_amount',
        'max_amount',
        'send_start_date',
        'send_end_date',
        'use_start_date',
        'use_end_date',
        'min_goods_amount',
        'review_status',
        'review_content'
    ];

    protected $guarded = [];
}
