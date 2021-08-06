<?php

namespace App\Services\IT\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface PositionServiceInterface
{
    public function all(): Builder;
    public function create(array $attributes): Model;
    public function find(int $id): Model;

    public function update(array $attributes, int $id): bool;
    public function destroy(int $id);

    public function dropdown(): Collection;
    public function dropdownForUser(): Collection;

}
