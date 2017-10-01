<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class LinkBrand
 */
class LinkBrand extends Model
{
    protected $table = 'link_brand';

    public $timestamps = false;

    protected $fillable = [
        'bid',
        'brand_id'
    ];

    protected $guarded = [];
}
