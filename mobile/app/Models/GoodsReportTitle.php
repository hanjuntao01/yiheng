<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GoodsReportTitle
 */
class GoodsReportTitle extends Model
{
    protected $table = 'goods_report_title';

    protected $primaryKey = 'title_id';

    public $timestamps = false;

    protected $fillable = [
        'type_id',
        'title_name',
        'is_show'
    ];

    protected $guarded = [];
}
