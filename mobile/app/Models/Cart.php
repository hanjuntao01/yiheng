<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Cart
 */
class Cart extends Model
{
    protected $table = 'cart';

    protected $primaryKey = 'rec_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'session_id',
        'goods_id',
        'goods_sn',
        'product_id',
        'group_id',
        'goods_name',
        'market_price',
        'goods_price',
        'goods_number',
        'goods_attr',
        'is_real',
        'extension_code',
        'parent_id',
        'rec_type',
        'is_gift',
        'is_shipping',
        'can_handsel',
        'model_attr',
        'goods_attr_id',
        'ru_id',
        'shopping_fee',
        'warehouse_id',
        'area_id',
        'add_time',
        'stages_qishu',
        'store_id',
        'freight',
        'tid',
        'shipping_fee',
        'store_mobile',
        'take_time',
        'is_checked'
    ];

    protected $guarded = [];

    public function goods()
    {
        return $this->hasOne(Goods::class, 'goods_id', "goods_id");
    }
}
