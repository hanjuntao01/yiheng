<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GoodsReportType
 */
class GoodsReportType extends Model
{
    protected $table = 'goods_report_type';

    protected $primaryKey = 'type_id';

    public $timestamps = false;

    protected $fillable = [
        'type_name',
        'type_desc',
        'is_show'
    ];

    protected $guarded = [];
}
