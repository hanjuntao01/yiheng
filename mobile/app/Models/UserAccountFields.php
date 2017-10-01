<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserAccountFields
 */
class UserAccountFields extends Model
{
    protected $table = 'user_account_fields';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'account_id',
        'bank_number',
        'real_name'
    ];

    protected $guarded = [];
}
