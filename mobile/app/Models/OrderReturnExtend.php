<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderReturnExtend
 */
class OrderReturnExtend extends Model
{
    protected $table = 'order_return_extend';

    public $timestamps = false;

    protected $fillable = [
        'ret_id',
        'return_number'
    ];

    protected $guarded = [];
}
