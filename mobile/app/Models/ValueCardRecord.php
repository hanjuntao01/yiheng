<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ValueCardRecord
 */
class ValueCardRecord extends Model
{
    protected $table = 'value_card_record';

    protected $primaryKey = 'rid';

    public $timestamps = false;

    protected $fillable = [
        'vc_id',
        'order_id',
        'use_val',
        'add_val',
        'record_time'
    ];

    protected $guarded = [];
}
