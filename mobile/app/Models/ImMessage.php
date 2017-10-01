<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ImMessage
 */
class ImMessage extends Model
{
    protected $table = 'im_message';

    public $timestamps = false;

    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'dialog_id',
        'message',
        'add_time',
        'user_type',
        'status'
    ];

    protected $guarded = [];
}
