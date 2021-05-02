<?php

namespace App\Services\IT\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface RoleServiceInterface
{
    public function find(int $id): Model;
    public function update(array $attributes, int $id): bool;
    public function delete(int $id): bool;
    public function create(array $attributes): Model;
    public function dropdown(...$slug): Collection;

    public function filter(Request $request);
    public function roleIn(...$slug): Collection;
    public function query();
}