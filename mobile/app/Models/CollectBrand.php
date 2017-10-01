<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CollectBrand
 */
class CollectBrand extends Model
{
    protected $table = 'collect_brand';

    protected $primaryKey = 'rec_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'brand_id',
        'add_time',
        'ru_id'
    ];

    protected $guarded = [];
}
