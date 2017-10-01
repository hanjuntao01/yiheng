<?php

namespace App\Contracts\Repositories\Goods;

/**
 * Interface GoodsRepositoryInterface
 * @package App\Contracts\Repositories\Goods
 */
interface GoodsRepositoryInterface
{
    /**
     * 新增单个商品
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * 获取单个商品信息
     * @param $id
     * @return mixed
     */
    public function get($id);

    /**
     * 更新商品信息
     * @param array $data
     * @return mixed
     */
    public function update(array $data);

    /**
     * 根据商品Id删除商品
     * @param $id
     * @return mixed
     */
    public function delete($id);

    /**
     * 商品搜索
     * @param array $data
     * @return mixed
     */
    public function search(array $data);

    /**
     * 获取商品SKU列表
     * @param $id
     * @return mixed
     */
    public function sku($id);

    /**
     * @return mixed
     */
    public function skuAdd();
}
