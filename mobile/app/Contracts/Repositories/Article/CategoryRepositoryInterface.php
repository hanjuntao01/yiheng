<?php

namespace App\Contracts\Repositories\Article;

/**
 * Interface CategoryRepositoryInterface
 * @package App\Contracts\Repositories\article
 */
interface CategoryRepositoryInterface
{
    /**
     * @param $cat_id
     * @param $columns
     * @param $offset
     * @return mixed
     */
    public function all($cat_id, $columns, $offset);

    /**
     * @param $cat_id
     * @param $columns
     * @return mixed
     */
    public function detail($cat_id, $columns);

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * @param array $data
     * @return mixed
     */
    public function update(array $data);

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id);
}
