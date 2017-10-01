<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MerchantsPrivilege
 */
class MerchantsPrivilege extends Model
{
    protected $table = 'merchants_privilege';

    public $timestamps = false;

    protected $fillable = [
        'action_list',
        'grade_id'
    ];

    protected $guarded = [];
}
