<?php

namespace App\Services;

use App\Repositories\Shop\ShopRepository;

class ShopService
{
    private $shopRepository;

    /**
     * ShopService constructor.
     * @param ShopRepository $shopRepository
     */
    public function __construct(ShopRepository $shopRepository)
    {
        $this->shopRepository = $shopRepository;
    }

    /**
     * 获取店铺名
     * @param $ruId
     * @return string
     */
    public function getShopName($ruId)
    {
        //店铺名称
        $shopInfo = $this->shopRepository->findBY('ru_id', $ruId);

        if (count($shopInfo) > 0) {
            $shopInfo = $shopInfo[0];
            if ($shopInfo['shopname_audit'] == 1) {
                if ($ruId > 0) {
                    $shopName = $shopInfo['brandName'] . $shopInfo['shopNameSuffix'];
                } else {
                    $shopName = $shopInfo['shop_name'];
                }
            } else {
                $shopName = $shopInfo['rz_shopName'];
            }
        } else {
            $shopName = "";
        }
        return $shopName;
    }
}
