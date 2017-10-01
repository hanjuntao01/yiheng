<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MerchantsNav
 */
class MerchantsNav extends Model
{
    protected $table = 'merchants_nav';

    public $timestamps = false;

    protected $fillable = [
        'ctype',
        'cid',
        'cat_id',
        'name',
        'ifshow',
        'vieworder',
        'opennew',
        'url',
        'type',
        'ru_id'
    ];

    protected $guarded = [];
}
