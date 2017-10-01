<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SellerShopinfo
 */
class SellerShopinfo extends Model
{
    protected $table = 'seller_shopinfo';

    public $timestamps = false;

    protected $fillable = [
        'ru_id',
        'shop_name',
        'shop_title',
        'shop_keyword',
        'country',
        'province',
        'city',
        'district',
        'shop_address',
        'seller_email',
        'kf_qq',
        'kf_ww',
        'meiqia',
        'kf_type',
        'kf_tel',
        'site_head',
        'mobile',
        'shop_logo',
        'logo_thumb',
        'street_thumb',
        'brand_thumb',
        'notice',
        'street_desc',
        'shop_header',
        'shop_color',
        'shop_style',
        'status',
        'apply',
        'is_street',
        'remark',
        'seller_theme',
        'win_goods_type',
        'store_style',
        'check_sellername',
        'shopname_audit',
        'shipping_id',
        'shipping_date',
        'longitude',
        'tengxun_key',
        'latitude',
        'kf_appkey',
        'kf_touid',
        'kf_logo',
        'kf_welcomeMsg',
        'kf_secretkey',
        'user_menu',
        'kf_im_switch',
        'seller_money',
        'frozen_money',
        'seller_templates',
        'templates_mode',
        'js_appkey',
        'js_appsecret'
    ];

    protected $guarded = [];

    /**
     * bolongsTO MerchantShopInfomation
     */
    public function MerchantsShopInformation()
    {
        return self::belongsTo('App\Models\MerchantsShopInformation', 'ru_id', 'user_id');
    }
}
