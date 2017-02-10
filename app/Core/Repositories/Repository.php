<?php

namespace App\Core\Repositories;

use Exception;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read string $entityName
 */
abstract class Repository
{
    /**
     * The entity.
     * @var object
     */
    protected $entity;

    /**
     * Create new repository instance.
     */
    public function __construct()
    {
        $this->makeEntity();
    }

    /**
     * Make entity by specified name.
     * @throws Exception
     */
    public function makeEntity()
    {
        if (! property_exists($this, 'entityName')) {
            throw new Exception('Class' . get_class($this) . ' must provide an attribute called \'entityName\'');
        }

        $entity = app($this->entityName);

        if (! $entity instanceof Model) {
            throw new Exception($this->entityName . ' must be an instance of Illuminate\\Database\\Eloquent\\Model');
        }

        $this->setEntity($entity);
    }

    /**
     * Return the query builder order by the specified attribute
     * @param  string $attr
     * @param  string $dir
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function orderBy($attr, $dir = 'asc')
    {
        return $this->entity->orderBy($attr, $dir);
    }

    /**
     * Get all available model instances.
     * @param  array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all($columns = ['*'])
    {
        return $this->entity->all($columns);
    }

    /**
     * Find a model instance by its attribute.
     * @param  string $attribute
     * @param  mixed $value
     * @param  bool $shouldThrowException
     * @return mixed
     */
    public function findBy($attribute, $value, $shouldThrowException = true)
    {
        $query = $this->entity->where($attribute, $value);

        return $shouldThrowException ? $query->firstOrFail() : $query->first();
    }

    /**
     * Find a model instance by its ID.
     * @param  int $id
     * @return mixed
     */
    public function findById($id)
    {
        return $this->entity->findOrFail($id);
    }

    /**
     * Find entities by their attribute values.
     * @param  string $attribute
     * @param  array $values
     * @param  array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function whereIn($attribute, array $values, $columns = ['*'])
    {
        return $this->entity->whereIn($attribute, $values)->get($columns);
    }

    /**
     * Find data by multiple fields
     * @param array $where
     * @param array $columns
     * @return mixed
     */
    public function where(array $where, $columns = ['*'])
    {
        $this->applyConditions($where);
        $data = $this->entity->get($columns);
        $this->resetEntity();
        return $data;
    }

    /**
     * Applies the given where conditions to the model.
     * @param array $where
     * @return void
     */
    protected function applyConditions(array $where)
    {
        foreach ($where as $field => $value) {
            if (is_array($value)) {
                list($field, $condition, $val) = $value;
                $this->entity = $this->entity->where($field, $condition, $val);
            } else {
                $this->entity = $this->entity->where($field, '=', $value);
            }
        }
    }

    /**
     * Create new model instance.
     * @param  array $data
     * @return Model
     */
    public function create(array $data)
    {
        return $this->entity->create($data);
    }

    /**
     * Update a model instance.
     * @param  array $data
     * @param  int|object $id
     * @param  string $attribute
     * @return mixed
     */
    public function update($data, $id, $attribute = 'id')
    {
        $fillableFields = $this->entity->getFillable();
        $data = array_only($data, $fillableFields);
        $id = is_object($id) ? $id->getKey() : $id;
        $this->entity->where($attribute, $id)->first()->update($data);

        return $this->findBy($attribute, $id);
    }

    /**
     * Update or Create an entity in repository
     * @throws ValidatorException
     * @param array $attributes
     * @param array $values
     * @return mixed
     */
    public function updateOrCreate(array $attributes, array $values = [])
    {
        $entity = $this->entity->updateOrCreate($attributes, $values);
        $this->resetEntity();

        return $entity;
    }

    /**
     * Delete an model instance.
     * @param  int|object $model
     * @return int
     */
    public function delete($model)
    {
        $modelKey = is_object($model) ? $model->getKey() : $model;

        return $this->entity->destroy($modelKey);
    }

    /**
     * Get the paginated list of latest model instances.
     * @param  integer $limit
     * @param  array|string $eagerLoad
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getLatestEntities($limit = null, $eagerLoad = [])
    {
        $limit = is_null($limit) ? config('common.pagination_per_page') : $limit;

        return $this->entity->with($eagerLoad)->latest()->paginate($limit);
    }

    /**
     * Insert new record for the given model.
     * @param  array $data
     * @return int
     */
    public function insert(array $data)
    {
        return $this->entity->insert($data);
    }

    /**
     * Batch inserting multiple database records.
     * @param  array $collection
     * @param  bool $returnLastId
     * @return int|void
     */
    public function batchInsert(array $collection, $returnLastId = true)
    {
        $records = array_map(function ($item) {
            $now = Carbon::now();
            $item[Model::CREATED_AT] = $now;
            $item[Model::UPDATED_AT] = $now;

            return $item;
        }, $collection);

        $this->insert($records);

        if ($returnLastId) {
            return $this->entity->max($this->entity->getKeyName());
        }
    }

    /**
     * Sets the value of entity name.
     * @param Model $entity
     * @return self
     */
    protected function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @throws RepositoryException
     */
    public function resetEntity()
    {
        $this->makeEntity();
    }
}
