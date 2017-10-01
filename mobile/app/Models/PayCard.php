<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PayCard
 */
class PayCard extends Model
{
    protected $table = 'pay_card';

    public $timestamps = false;

    protected $fillable = [
        'card_number',
        'card_psd',
        'user_id',
        'used_time',
        'status',
        'c_id'
    ];

    protected $guarded = [];
}
