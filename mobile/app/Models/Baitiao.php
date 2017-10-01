<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Baitiao
 */
class Baitiao extends Model
{
    protected $table = 'baitiao';

    protected $primaryKey = 'baitiao_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'amount',
        'repay_term',
        'over_repay_trem',
        'add_time'
    ];

    protected $guarded = [];
}
