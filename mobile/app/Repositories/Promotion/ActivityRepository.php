<?php

namespace App\Repositories\Promotion;

use App\Contracts\Repositories\RepositoryInterface;
use App\Models\Activity;

class ActivityRepository implements RepositoryInterface
{
    public function all($columns = array('*'))
    {
    }

    public function paginate($perPage = 15, $columns = array('*'))
    {
    }

    public function create(array $data)
    {
    }

    public function update(array $data, $id)
    {
    }

    public function delete($id)
    {
    }

    public function find($id, $columns = array('*'))
    {
    }

    public function findBy($field, $value, $columns = array('*'))
    {
    }
}
