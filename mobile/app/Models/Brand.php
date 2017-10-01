<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Brand
 */
class Brand extends Model
{
    protected $table = 'brand';

    protected $primaryKey = 'brand_id';

    public $timestamps = false;

    protected $fillable = [
        'brand_name',
        'brand_letter',
        'brand_first_char',
        'brand_logo',
        'index_img',
        'brand_desc',
        'site_url',
        'sort_order',
        'is_show',
        'is_delete',
        'audit_status',
        'add_time'
    ];

    protected $guarded = [];
}
