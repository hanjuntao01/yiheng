<?php

namespace App\Repositories\Store;

use App\Contracts\Repositories\Store\StoreRepositoryInterface;
use App\Models\MerchantsShopInformation;
use App\Models\Goods;
use App\Models\CollectStore;

class StoreRepository implements StoreRepositoryInterface
{
    public function all()
    {
        $store = MerchantsShopInformation::select('shop_id', 'user_id', 'rz_shopName')
                ->with(['sellershopinfo'=>function ($query) {
                    $query->select('logo_thumb', 'ru_id');
                }])
                ->get()
                ->toArray;
        return $store;
    }

    public function detail($id)
    {
        $detail = MerchantsShopInformation::select('*')
                ->with(['sellershopinfo'=>function ($query) {
                    $query->select('*');
                }])
                ->where('user_id', $id)
                ->get()
                ->toArray();

        return $detail;
    }

    public function goods($id, $num)
    {
        $goods = Goods::select('goods_id', 'goods_name', 'goods_thumb', 'shop_price', 'cat_id', 'market_price', 'goods_number')
                ->where('user_id', $id)
                ->where('is_on_sale', '1')
                ->where('is_alone_sale', '1')
                ->limit($num)
                ->get()
                ->toArray();
        return $goods;
    }

    public function collect($shopid, $user, $status, $time, $attention)
    {
        if($user > 0 && $attention >1){
            $collectnum = CollectStore::select('rec_id')
                ->where('user_id', $user)
                ->where('ru_id', $shopid)
                ->get()
                ->toArray();
        }
        if($status > 0){
            $collect = CollectStore::where('user_id', $user)
                ->where('ru_id', $shopid)
                ->delete();
        }
        if($attention == 1){
            $collectadd = CollectStore::insertGetId([
                ['user_id' => $user, 'votes' => 0],
                ['ru_id' => $shopid, 'votes' => 0],
                ['add_time' => $time, 'votes' => 0],
                ['is_attention' => $attention, 'votes' => 0]
            ]);
        }
    }

    public function collnum($ruId)
    {
        return CollectStore::where('ru_id', $ruId)
                ->count();
    }

    public function delete($id)
    {
    }

    public function find($id, $columns = array('*'))
    {
    }

    public function findBy($field, $value, $columns = array('*'))
    {
    }
}
