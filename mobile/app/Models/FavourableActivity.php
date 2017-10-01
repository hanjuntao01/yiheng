<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FavourableActivity
 */
class FavourableActivity extends Model
{
    const    FAT_GOODS                 = 0; // 送赠品或优惠购买
    const    FAT_PRICE                 = 1; // 现金减免
    const    FAT_DISCOUNT              = 2; // 价格打折优惠

    /* 优惠活动的优惠范围 */
    const   FAR_ALL                   = 0; // 全部商品
    const   FAR_CATEGORY              = 1; // 按分类选择
    const   FAR_BRAND                 = 2; // 按品牌选择
    const   FAR_GOODS                 = 3; // 按商品选择

    protected $table = 'favourable_activity';

    protected $primaryKey = 'act_id';

    public $timestamps = false;

    protected $fillable = [
        'act_name',
        'start_time',
        'end_time',
        'user_rank',
        'act_range',
        'act_range_ext',
        'min_amount',
        'max_amount',
        'act_type',
        'act_type_ext',
        'activity_thumb',
        'gift',
        'sort_order',
        'user_id',
        'userFav_type',
        'review_status',
        'review_content'
    ];

    protected $guarded = [];
}
