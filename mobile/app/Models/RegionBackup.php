<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RegionBackup
 */
class RegionBackup extends Model
{
    protected $table = 'region_backup';

    protected $primaryKey = 'region_id';

    public $timestamps = false;

    protected $fillable = [
        'parent_id',
        'region_name',
        'region_type',
        'agency_id'
    ];

    protected $guarded = [];
}
