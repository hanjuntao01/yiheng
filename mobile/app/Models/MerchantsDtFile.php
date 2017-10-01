<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MerchantsDtFile
 */
class MerchantsDtFile extends Model
{
    protected $table = 'merchants_dt_file';

    protected $primaryKey = 'dtf_id';

    public $timestamps = false;

    protected $fillable = [
        'cat_id',
        'dt_id',
        'user_id',
        'permanent_file',
        'permanent_date',
        'cate_title_permanent'
    ];

    protected $guarded = [];
}
