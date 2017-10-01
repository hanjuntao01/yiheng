<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ReturnAction
 */
class ReturnAction extends Model
{
    protected $table = 'return_action';

    protected $primaryKey = 'action_id';

    public $timestamps = false;

    protected $fillable = [
        'ret_id',
        'action_user',
        'return_status',
        'refound_status',
        'action_place',
        'action_note',
        'log_time'
    ];

    protected $guarded = [];
}
