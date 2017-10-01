<?php

namespace App\Services;

use App\Repositories\Store\StoreRepository;

class StoreService
{
    private $storeRepository;

    /**
     * StoreService constructor.
     * @param StoreRepository $storeRepository
     */
    public function __construct(StoreRepository $storeRepository)
    {
        $this->storeRepository = $storeRepository;
    }

    /**
     * 店铺列表
     * @return array
     */
    public function storeList()
    {
        $list = $this->storeRepository->all();

        return $list;
    }

    /**
     * 店铺详情
     * @return array
     */
    public function detail($id, $num = 10)
    {
        $detail = $this->storeRepository->detail($id);
        $detail['0']['sellershopinfo']['shop_logo'] = str_replace('../', '', $detail['0']['sellershopinfo']['shop_logo']);
        $goods = $this->storeRepository->goods($id, $num);
        foreach ($goods as $key => $value) {
            $goods[$key]['goods_name'] = $value['goods_name'];
            $goods[$key]['goods_thumb'] = get_image_path($value['goods_thumb']);
            $goods[$key]['shop_price'] = price_format($value['shop_price'], true);
            $goods[$key]['yuan_shop'] = $value['shop_price'];
            $goods[$key]['cat_id'] = $value['cat_id'];
            $goods[$key]['market_price'] = price_format($value['market_price'], true);
            $goods[$key]['yuan_market'] = $value['market_price'];
            $goods[$key]['goods_number'] = $value['goods_number'];
        }
        $collnum = $this->storeRepository->collnum($id);
        $list['detail'] = $detail['0'];
        $list['goods'] = $goods;
        $list['collnum'] = $collnum;

        return $list;
    }

    /**
     * 关注店铺
     * @return array
     */
    public function collect($shopid)
    {
        $time = gmtime();
        $shopid = input('ruid', 0, 'intval');
        if (!empty($shopid) && $_SESSION['user_id'] > 0) {
            $status = $this->storeRepository->collect($shopid, $_SESSION['user_id']);
            if (count($status) > 0) {
                $status = $this->storeRepository->collect('', '', $status['rec_id']);
            } else {
                $status = $this->storeRepository->collectad($shopid, $_SESSION['user_id'], '', $time, 1);
            }
        }
    }
}
