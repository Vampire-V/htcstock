<?php

namespace App\Services\IT\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface AccessoriesServiceInterface
{
    public function all(): Builder;
    public function filter(Request $request);
    public function create(array $attributes): Model;
    public function find(int $id): Model;

    public function update(array $attributes, int $id): bool;
    public function destroy(int $id);
    public function remove($id);

    public function sumAccessories();
    public function dropdown(): Collection;
}
