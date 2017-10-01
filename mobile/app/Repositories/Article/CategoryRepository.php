<?php

namespace App\Repositories\Article;

use App\Contracts\Repositories\Article\CategoryRepositoryInterface;
use App\Models\ArticleCat;

class CategoryRepository implements CategoryRepositoryInterface
{

    /**
     * 返回指定文章类别的子类
     * @param $cat_id
     * @param array $columns
     * @param int $size
     * @return mixed
     */
    public function all($cat_id = 0, $columns = ['*'], $size = 100)
    {
        if (is_array($cat_id)) {
            $field = key($cat_id);
            $value = $cat_id[$field];
            $model = ArticleCat::where($field, '=', $value);
        } else {
            $model = ArticleCat::where('parent_id', $cat_id);
        }

        return $model->orderBy('sort_order')
            ->orderBy('cat_id')
            ->paginate($size, $columns)
            ->toArray();
    }

    /**
     * 返回指定文章类别的详情
     * @param $cat_id
     * @param array $columns
     * @return mixed
     */
    public function detail($cat_id, $columns = ['*'])
    {
        if (is_array($cat_id)) {
            $field = key($cat_id);
            $value = $cat_id[$field];
            $model = ArticleCat::where($field, '=', $value)->first($columns);
        } else {
            $model = ArticleCat::find($cat_id, $columns);
        }

        return $model->toArray();
    }

    /**
     * @return mixed
     */
    public function create(array $data)
    {
        return false;
    }

    /**
     * @return mixed
     */
    public function update(array $data)
    {
        return false;
    }

    /**
     * @return mixed
     */
    public function delete($id)
    {
        return false;
    }
}
