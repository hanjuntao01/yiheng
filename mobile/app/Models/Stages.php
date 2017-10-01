<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Stages
 */
class Stages extends Model
{
    protected $table = 'stages';

    protected $primaryKey = 'stages_id';

    public $timestamps = false;

    protected $fillable = [
        'order_sn',
        'stages_total',
        'stages_one_price',
        'yes_num',
        'create_date',
        'repay_date'
    ];

    protected $guarded = [];
}
