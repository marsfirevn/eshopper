<?php
/**
 * Created by PhpStorm.
 * User: huukimit
 * Date: 19/02/2017
 * Time: 07:48
 */

namespace App\Core\Repositories\Contracts;

interface RepositoryInterface
{
    public function all($columns = ['*']);

    public function paginate($perPage = 15, $columns = ['*']);

    public function create(array $data);

    public function update(array $data, $id);

    public function delete($id);

    public function findById($id, $columns = ['*']);

    public function findBy($field, $value, $columns = ['*']);
}
