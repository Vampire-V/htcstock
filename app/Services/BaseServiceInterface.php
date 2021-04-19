<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface BaseServiceInterface
 * @package App\Services
 */
interface BaseServiceInterface
{
    /**
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model;

    public function insert(array $attributes): bool;

    /**
     * @param $id
     * @return Model
     */
    public function find(int $id): Model;

    /**
     * @param $attributes
     * @param $id
     * @return bool
     */
    public function update(array $attributes, int $id): bool;

    /**
     * @param $id
     * @return bool
     */
    // public function destroyIn(array $id): bool;
}
