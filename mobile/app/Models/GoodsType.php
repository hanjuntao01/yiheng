<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GoodsType
 */
class GoodsType extends Model
{
    protected $table = 'goods_type';

    protected $primaryKey = 'cat_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'cat_name',
        'enabled',
        'attr_group'
    ];

    protected $guarded = [];
}
