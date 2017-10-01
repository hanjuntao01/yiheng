<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SellerShopslide
 */
class SellerShopslide extends Model
{
    protected $table = 'seller_shopslide';

    public $timestamps = false;

    protected $fillable = [
        'ru_id',
        'img_url',
        'img_link',
        'img_desc',
        'img_order',
        'slide_type',
        'is_show',
        'seller_theme',
        'install_img'
    ];

    protected $guarded = [];
}
