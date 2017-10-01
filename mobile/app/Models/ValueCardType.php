<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ValueCardType
 */
class ValueCardType extends Model
{
    protected $table = 'value_card_type';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'vc_desc',
        'vc_value',
        'vc_prefix',
        'vc_dis',
        'vc_limit',
        'use_condition',
        'use_merchants',
        'spec_goods',
        'spec_cat',
        'vc_indate',
        'is_rec',
        'add_time'
    ];

    protected $guarded = [];
}
