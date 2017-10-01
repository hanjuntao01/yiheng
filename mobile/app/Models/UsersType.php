<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UsersType
 */
class UsersType extends Model
{
    protected $table = 'users_type';

    protected $primaryKey = 'user_id';

    public $timestamps = false;

    protected $fillable = [
        'enterprise_personal',
        'companyname',
        'contactname',
        'companyaddress',
        'industry',
        'surname',
        'givenname',
        'agreement',
        'country',
        'province',
        'city',
        'district'
    ];

    protected $guarded = [];
}
