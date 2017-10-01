<?php

namespace App\Api\Controllers;

use App\Api\Foundation\Controller;
use App\Services\CategoryService;
use Illuminate\Http\Request;

/**
 * Class CategoryController
 * @package App\Api\Controllers
 */
class CategoryController extends Controller
{

    /** @var  $category */
    protected $category;

    /**
     * Category constructor.
     * @param CategoryService $category
     */
    public function __construct(CategoryService $category)
    {
        parent::__construct();
        $this->category = $category;
    }

    /**
     * 获取商品分类列表
     */
    public function categoryList()
    {
        $data = $this->category->categoryList();

        return $this->apiReturn($data);
    }

    /**
     * 商品分类详情
     * @param Request $request
     * @return mixed
     */
    public function categoryDetail(Request $request)
    {
        $pattern = [
            'id' => 'required|integer'
        ];

        $this->validate($request, $pattern);

        $data = $this->category->categoryDetail($request->get('id'));

        return $this->apiReturn($data);
    }
}
