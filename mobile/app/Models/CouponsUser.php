<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CouponsUser
 */
class CouponsUser extends Model
{
    protected $table = 'coupons_user';

    protected $primaryKey = 'uc_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'cou_id',
        'is_use',
        'uc_sn',
        'order_id',
        'is_use_time'
    ];

    protected $guarded = [];
}
