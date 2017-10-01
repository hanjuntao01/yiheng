<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GoodsReportImg
 */
class GoodsReportImg extends Model
{
    protected $table = 'goods_report_img';

    protected $primaryKey = 'img_id';

    public $timestamps = false;

    protected $fillable = [
        'goods_id',
        'report_id',
        'user_id',
        'img_file'
    ];

    protected $guarded = [];
}
