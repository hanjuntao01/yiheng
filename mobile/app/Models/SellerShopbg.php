<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SellerShopbg
 */
class SellerShopbg extends Model
{
    protected $table = 'seller_shopbg';

    public $timestamps = false;

    protected $fillable = [
        'bgimg',
        'bgrepeat',
        'bgcolor',
        'show_img',
        'is_custom',
        'ru_id',
        'seller_theme'
    ];

    protected $guarded = [];
}
