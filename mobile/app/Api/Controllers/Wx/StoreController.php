<?php

namespace App\Api\Controllers\Wx;

use App\Api\Controllers\Controller;
use App\Services\StoreService;
use App\Repositories\Store\StoreRepository;
use Illuminate\Http\Request;

/**
 * Class StoreController
 * @package App\Api\Controllers\Wx
 */
class StoreController extends Controller
{
    protected $store;

    /**
     * Store constructor.
     * @param StoreRepository $store
     */
    public function __construct(StoreService $storeService)
    {
        $this->store = $storeService;
    }

    /**
     * 类别列表
     * @return mixed
     */
    public function index()
    {
        return $this->store->storeList();
    }

    /**
     * 类别详情
     * @param $id
     * @return mixed
     */
    public function detail(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|int',
        ]);
        return $this->store->detail($request->get('id'));
    }

    /**
     * 关注
     * @param $id
     * @return mixed
     */
    public function detail(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|int',
        ]);
        return $this->store->detail($request->get('id'));
    }

}
