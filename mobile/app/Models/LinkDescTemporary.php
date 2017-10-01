<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class LinkDescTemporary
 */
class LinkDescTemporary extends Model
{
    protected $table = 'link_desc_temporary';

    public $timestamps = false;

    protected $fillable = [
        'goods_id'
    ];

    protected $guarded = [];
}
