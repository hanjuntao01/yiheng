<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ValueCard
 */
class ValueCard extends Model
{
    protected $table = 'value_card';

    protected $primaryKey = 'vid';

    public $timestamps = false;

    protected $fillable = [
        'tid',
        'value_card_sn',
        'value_card_password',
        'user_id',
        'vc_value',
        'card_money',
        'bind_time',
        'end_time'
    ];

    protected $guarded = [];
}
