<?php

namespace App\Api\Controllers;

use App\Api\Foundation\Controller;
use App\Models\GoodsTransport;
use App\Services\GoodsService;

/**
 * Class GoodsController
 * @package App\Api\Controllers
 */
class GoodsController extends Controller
{

    /** @var  $goodsService */
    protected $goodsService;

    /** @var  $goodsTransport */
    protected $goodsTransport;

    /**
     * Goods constructor.
     * @param GoodsService $goodsService
     * @param GoodsTransport $goodsTransport
     */
    public function __construct(GoodsService $goodsService, GoodsTransport $goodsTransport)
    {
        parent::__construct();
        $this->goodsService = $goodsService;
        $this->goodsTransport = $goodsTransport;
    }

    /**
     * 获取商品列表
     */
    public function actionList()
    {
    }

    /**
     * 获取商品详情
     */
    public function actionDetail()
    {
    }

    /**
     * 获取商品SKU
     */
    public function actionSku()
    {
    }

    /**
     * 获取商品配件
     */
    public function actionFittings()
    {
    }
}
