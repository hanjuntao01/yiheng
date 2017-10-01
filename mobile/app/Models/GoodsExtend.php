<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GoodsExtend
 */
class GoodsExtend extends Model
{
    protected $table = 'goods_extend';

    protected $primaryKey = 'extend_id';

    public $timestamps = false;

    protected $fillable = [
        'goods_id',
        'is_reality',
        'is_return',
        'is_fast',
        'width',
        'height',
        'depth',
        'origincountry',
        'originplace',
        'assemblycountry',
        'barcodetype',
        'catena',
        'isbasicunit',
        'packagetype',
        'grossweight',
        'netweight',
        'netcontent',
        'licensenum',
        'healthpermitnum'
    ];

    protected $guarded = [];
}
