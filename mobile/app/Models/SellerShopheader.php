<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SellerShopheader
 */
class SellerShopheader extends Model
{
    protected $table = 'seller_shopheader';

    public $timestamps = false;

    protected $fillable = [
        'content',
        'headtype',
        'headbg_img',
        'shop_color',
        'seller_theme',
        'ru_id'
    ];

    protected $guarded = [];
}
