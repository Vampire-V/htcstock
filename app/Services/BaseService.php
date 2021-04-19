<?php

namespace App\Services;

use App\Services\BaseServiceInterface;
use Illuminate\Database\Eloquent\Model;

class BaseService implements BaseServiceInterface
{
    /**      
     * @var Model      
     */
    protected $model;

    /**      
     * BaseService constructor.      
     *      
     * @param Model $model      
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes): Model
    {
        try {
            return $this->model->create($attributes);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * @param array $attributes
     *
     * @return bool
     */
    public function insert(array $attributes): bool
    {
        try {
            return $this->model->insert($attributes);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * @param $id
     * @return Model
     */
    public function find($id): Model
    {
        try {
            return $this->model->findOrFail($id);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * @param array $attributes
     *
     * @return bool
     */
    public function update(array $attributes, int $id): bool
    {
        try {
            $model = $this->model->find($id);
            return $model->update($attributes);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function destroy($id)
    {
        try {
            return $this->model->destroy($id);
        } catch (\Throwable $th) {
            // return $th->getMessage();
            throw $th;
        }
    }
}
