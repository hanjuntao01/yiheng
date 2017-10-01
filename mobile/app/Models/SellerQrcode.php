<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SellerQrcode
 */
class SellerQrcode extends Model
{
    protected $table = 'seller_qrcode';

    protected $primaryKey = 'qrcode_id';

    public $timestamps = false;

    protected $fillable = [
        'ru_id',
        'qrcode_thumb'
    ];

    protected $guarded = [];
}
