<?php

namespace App\Contracts\Repositories\Brand;

/**
 * Interface BrandRepositoryInterface
 * @package App\Contracts\Repositories\brand
 */
interface BrandRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getAllBrands();

    /**
     * @param $id
     * @return mixed
     */
    public function getBrandDetail($id);
}
