<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ReturnCause
 */
class ReturnCause extends Model
{
    protected $table = 'return_cause';

    protected $primaryKey = 'cause_id';

    public $timestamps = false;

    protected $fillable = [
        'cause_name',
        'parent_id',
        'sort_order',
        'is_show'
    ];

    protected $guarded = [];
}
