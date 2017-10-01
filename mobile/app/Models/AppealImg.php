<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AppealImg
 */
class AppealImg extends Model
{
    protected $table = 'appeal_img';

    protected $primaryKey = 'img_id';

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'complaint_id',
        'ru_id',
        'img_file'
    ];

    protected $guarded = [];
}
