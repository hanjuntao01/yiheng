<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MerchantsShopBrand
 */
class MerchantsShopBrand extends Model
{
    protected $table = 'merchants_shop_brand';

    protected $primaryKey = 'bid';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'bank_name_letter',
        'brandName',
        'brandFirstChar',
        'brandLogo',
        'brandType',
        'brand_operateType',
        'brandEndTime',
        'brandEndTime_permanent',
        'site_url',
        'brand_desc',
        'sort_order',
        'is_show',
        'is_delete',
        'major_business',
        'audit_status',
        'add_time'
    ];

    protected $guarded = [];
}
