<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SaleNotice
 */
class SaleNotice extends Model
{
    protected $table = 'sale_notice';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'goods_id',
        'cellphone',
        'email',
        'hopeDiscount',
        'status',
        'send_type',
        'add_time',
        'mark'
    ];

    protected $guarded = [];
}
