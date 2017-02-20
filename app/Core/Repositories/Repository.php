<?php
/**
 * Created by PhpStorm.
 * User: huukimit
 * Date: 19/02/2017
 * Time: 07:46
 */

namespace App\Core\Repositories;

use App\Core\Repositories\Contracts\RepositoryInterface;
use App\Core\Repositories\Exceptions\RepositoryException;
use App\Entities\BaseUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class Repository
 *
 * @package App\Core\NewRepositories
 * @property Model $model
 * @property string $modelName
 */
abstract class Repository implements RepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * Repository constructor.
     *
     * @throws RepositoryException
     */
    public function __construct()
    {
        $this->makeModel();
    }

    /**
     * Make model instance
     *
     * @return Model
     * @throws RepositoryException
     */
    public function makeModel()
    {
        $model = app($this->model());

        if (!$model instanceof Model) {
            $msg = "Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model";
            throw new RepositoryException($msg);
        }

        return $this->model = $model;
    }

    /**
     * Specify Model class name
     *
     * @return string
     * @throws RepositoryException
     */
    public function model()
    {
        if (!property_exists($this, 'modelName')) {
            $message = 'Class' . get_class($this) . ' must provide an attribute called \'modelName\'';
            throw new RepositoryException($message);
        }

        return $this->modelName;
    }

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function findById($id, $columns = ['*'])
    {
        return $this->model->find($id, $columns);
    }

    /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return Model|null
     */
    public function findBy($attribute, $value, $columns = ['*'])
    {
        return $this->model->where($attribute, '=', $value)->first($columns);
    }

    /**
     * @param array $columns
     * @return Collection
     */
    public function all($columns = ['*'])
    {
        return $this->model->get($columns);
    }

    /**
     * @param int $perPage
     * @param array $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage = 15, $columns = ['*'])
    {
        return $this->model->paginate($perPage, $columns);
    }

    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * @param array $data
     * @param int|Model $id
     * @param string $attribute
     * @return int Number of updated
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        if (is_object($id)) {
            $id = $attribute === 'id' ? $id->id : $id->{$attribute};
        }

        return $this->model->where($attribute, '=', $id)->update($data);
    }

    /**
     * @param $id
     * @return int Number of records deleted
     */
    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    /**
     * Applies the given where conditions to the model.
     *
     * @param array $where Support ['field' => 'value', 'field2' => ['field2', '>=', 5]]
     */
    protected function applyConditions(array $where)
    {
        foreach ($where as $field => $value) {
            if (is_array($value)) {
                list($field, $condition, $val) = $value;
                $this->model = $this->model->where($field, $condition, $val);
            } else {
                $this->model = $this->model->where($field, '=', $value);
            }
        }
    }
}
