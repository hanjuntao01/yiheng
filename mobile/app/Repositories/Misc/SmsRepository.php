<?php

namespace App\Repositories\Misc;

use App\Contracts\Repositories\RepositoryInterface;

class SmsRepository implements RepositoryInterface
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