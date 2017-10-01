<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MerchantsShopBrandfile
 */
class MerchantsShopBrandfile extends Model
{
    protected $table = 'merchants_shop_brandfile';

    protected $primaryKey = 'b_fid';

    public $timestamps = false;

    protected $fillable = [
        'bid',
        'qualificationNameInput',
        'qualificationImg',
        'expiredDateInput',
        'expiredDate_permanent'
    ];

    protected $guarded = [];
}
