<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PayCardType
 */
class PayCardType extends Model
{
    protected $table = 'pay_card_type';

    protected $primaryKey = 'type_id';

    public $timestamps = false;

    protected $fillable = [
        'type_name',
        'type_money',
        'type_prefix',
        'use_end_date'
    ];

    protected $guarded = [];
}
