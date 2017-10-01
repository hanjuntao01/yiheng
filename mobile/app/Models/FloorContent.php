<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FloorContent
 */
class FloorContent extends Model
{
    protected $table = 'floor_content';

    protected $primaryKey = 'fb_id';

    public $timestamps = false;

    protected $fillable = [
        'filename',
        'region',
        'id_name',
        'brand_id',
        'brand_name',
        'theme'
    ];

    protected $guarded = [];
}
