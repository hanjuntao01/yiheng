<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BrandExtend
 */
class BrandExtend extends Model
{
    protected $table = 'brand_extend';

    public $timestamps = false;

    protected $fillable = [
        'brand_id',
        'is_recommend'
    ];

    protected $guarded = [];
}
