<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserBank
 */
class UserBank extends Model
{
    protected $table = 'user_bank';

    public $timestamps = false;

    protected $fillable = [
        'bank_name',
        'bank_card',
        'bank_region',
        'bank_user_name',
        'user_id'
    ];

    protected $guarded = [];
}
