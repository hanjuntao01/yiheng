<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RegionWarehouse
 */
class RegionWarehouse extends Model
{
    protected $table = 'region_warehouse';

    protected $primaryKey = 'region_id';

    public $timestamps = false;

    protected $fillable = [
        'regionId',
        'parent_id',
        'region_name',
        'region_type',
        'agency_id'
    ];

    protected $guarded = [];
}
