<?php

namespace App\Services;

use App\Repositories\Category\CategoryRepository;

class CategoryService
{
    private $categoryRepository;

    /**
     * CategoryService constructor.
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * 分类列表
     * @return array
     */
    public function categoryList()
    {
        $list = $this->categoryRepository->getAllCategorys();

        return $list;
    }

    /**
     * 商品分类详情
     * 商品列表
     * @param $catId
     * @return array
     */
    public function categoryDetail($catId)
    {
        $list = $this->categoryRepository->getCategoryGetGoods($catId);

        return $list;
    }
}
